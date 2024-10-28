<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProduct;

class OrderProductController extends Controller
{
    public function index(Request $request) {
        // Fetch Order_Products with filtering capabilities
        $query = OrderProduct::with(['order', 'product']); 

        // Debugging the initial query
        dump("Initial Query", $query->toSql());

        // Filter by order name (from orders table)
        if ($request->has('order_name')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->order_name . '%');
            });
            dump("Filtering by order name:", $request->order_name);
        }

        // Filter by product name (from products table)
        if ($request->has('product_name')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->product_name . '%');
            });
            dump("Filtering by product name:", $request->product_name);
        }

        // Filter by description (from orders table)
        if ($request->has('description')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->where('description', 'like', '%' . $request->description . '%');
            });
            dump("Filtering by description:", $request->description);
        }

        // Filter by order date (from orders table)
        if ($request->has('order_date_from') && $request->has('order_date_to')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereBetween('order_date', [$request->order_date_from, $request->order_date_to]);
            });
            dump("Filtering by order date range:", [$request->order_date_from, $request->order_date_to]);
        }

        // Filter by exact order date
        if ($request->has('order_date')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('order_date', $request->order_date);
            });
            dump("Filtering by exact order date:", $request->order_date);
        }

        // Execute the query and paginate results
        $result = $query->paginate(10);
        dd("Paginated Results:", $result); // Output the final results
    }

    public function show($id) {
        $orderProduct = OrderProduct::with(['order', 'product'])->findOrFail($id);
        dd("Showing OrderProduct:", $orderProduct); // Debug output
        return response()->json($orderProduct);
    }

    public function store(Request $request) {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        dump("Incoming data for store:", $request->all()); // Debugging incoming request data

        $orderProduct = OrderProduct::create($request->only(['order_id', 'product_id', 'quantity']));
        dd("OrderProduct created:", $orderProduct); // Debug output
        return response()->json($orderProduct, 201);
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'order_date' => 'required|date',
            'products' => 'array',
            'removedProducts' => 'array'
        ]);

        dump("Incoming data for update:", $request->all()); // Debugging incoming request data

        // Find the order
        $order = Order::findOrFail($id);
        
        // Update order properties
        $order->name = $request->name;
        $order->description = $request->description;
        $order->order_date = $request->order_date;
        $order->save();

        dump("Order updated:", $order); // Debug output after updating order

        // Sync products (this will handle both adding and updating quantities)
        $products = collect($request->products)->mapWithKeys(function ($product) {
            return [$product['id'] => ['quantity' => $product['quantity']]];
        });
        
        dump("Syncing products:", $products); // Debug output
        $order->products()->sync($products);

        // Remove products specified in removedProducts
        if ($request->has('removedProducts') && is_array($request->removedProducts)) {
            $order->products()->detach(collect($request->removedProducts)->pluck('id')->toArray());
            dump("Removed products:", $request->removedProducts); // Debug output
        }

        return response()->json(['message' => 'Order updated successfully!']);
    }

    public function destroy($orderId, $productId) {
        $orderProduct = OrderProduct::where('order_id', $orderId)->where('product_id', $productId)->first();

        if (!$orderProduct) {
            dump("Order-Product relationship not found", ['orderId' => $orderId, 'productId' => $productId]);
            return response()->json(['message' => 'Order-Product relationship not found'], 404);
        }

        $orderProduct->delete();
        dump("Order-Product relationship deleted", ['orderId' => $orderId, 'productId' => $productId]);
        return response()->json(null, 204);
    }

    public function associateProductsToOrder(Request $request, $orderId) {
        $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        dump("Associating products to order:", $request->products); // Debugging incoming request data

        foreach ($request->products as $product) {
            OrderProduct::create([
                'order_id' => $orderId,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
            ]);
        }

        dump("Products associated with order:", ['orderId' => $orderId, 'products' => $request->products]); // Debug output
        return response()->json(['message' => 'Products associated with order successfully.'], 201);
    }
}

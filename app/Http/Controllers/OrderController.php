<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request) {
        $query = Order::query();

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by description
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        // Filter by order date range
        if ($request->filled('order_date_from') && $request->filled('order_date_to')) {
            $query->whereBetween('order_date', [$request->order_date_from, $request->order_date_to]);
        }

        // Filter by exact order date
        if ($request->filled('order_date')) {
            $query->whereDate('order_date', $request->order_date);
        }

        // Fetch
        $orders = $query->get();

        return response()->json($orders);
    }

    public function show($id) {
        // Fetch a single order with related products
        $order = Order::with(['products' => function($query) {
            $query->withPivot('quantity');
        }])->findOrFail($id);
    
        return response()->json($order);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'order_date' => 'required|date',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
    
        // Create order
        $order = Order::create($validatedData);
    
        // Attach products to order
        foreach ($request->products as $product) {
            $order->products()->attach($product['id'], ['quantity' => $product['quantity']]);
        }
    
        return response()->json($order, 201);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Update order details
        $order->name = $request->name;
        $order->description = $request->description;
        $order->order_date = $request->order_date;
        $order->save();
        
        // Update products
        $order->products()->sync(
            collect($request->products)->mapWithKeys(function ($product) {
                return [$product['id'] => ['quantity' => $product['quantity']]];
            })
        );

        // Handle removed products
        if (isset($request->removedProducts)) {
            foreach ($request->removedProducts as $removedProduct) {
                $order->products()->detach($removedProduct['id']);
            }
        }
        
        return response()->json(['message' => 'Order updated successfully.']);
    }

    public function destroy($id) {
        $order = Order::findOrFail($id);
        $order->delete();
        
        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Fetch all products
    public function index()
    {
        $products = Product::all(); // Fetch all products
        return response()->json($products);
    }

    // Fetch a single product by ID
    public function show($id)
    {
        $product = Product::findOrFail($id); // Fetch product by ID
        return response()->json($product);
    }

    // Create a new product
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::create($validatedData); // Create new product
        return response()->json($product, 201); // Return newly created product
    }

    // Update a product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id); // Find product by ID

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'price' => 'numeric|min:0',
        ]);

        $product->update($validatedData); // Update product with new data
        return response()->json($product); // Return updated product
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::findOrFail($id); // Find product by ID
        $product->delete(); // Delete the product
        return response()->json(null, 204); // Return success response
    }

    // Fetch products for a specific order
    public function productsForOrder($orderId)
    {
        $products = Product::all(); // Adjust this if you want to filter products specific to the order
        return response()->json($products);
    }
}

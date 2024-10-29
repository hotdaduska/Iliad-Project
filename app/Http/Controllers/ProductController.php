<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Fetch all products
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    // Fetch a single product by ID
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    // Create new product
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::create($validatedData);
        return response()->json($product, 201);
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'price' => 'numeric|min:0',
        ]);

        $product->update($validatedData);
        return response()->json($product);
    }

    // Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(null, 204);
    }

    // Fetch products for specific order
    public function productsForOrder($orderId)
    {
        $products = Product::all();
        return response()->json($products);
    }
}

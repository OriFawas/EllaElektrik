<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Get all products (for API)
     */
    public function index(Request $request)
    {
        // Check token abilities (fallback if middleware fails)
        if ($request->expectsJson()) {
            $user = $request->user();
            if ($user && $user->currentAccessToken()) {
                $abilities = $user->currentAccessToken()->abilities;
                if (!in_array('admin:*', $abilities)) {
                    return response()->json([
                        'message' => 'Insufficient permissions. Admin access required.',
                        'your_abilities' => $abilities
                    ], 403);
                }
            }
        }
        
        $products = Product::with(['subkategori.kategori'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($products);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'subkategori_product_id' => 'nullable|exists:subkategori_products,id',
            'watt' => 'nullable|integer',
            'brand' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:5120',
            'specs' => 'nullable|array',
            'specs.*.key' => 'nullable|string|max:255',
            'specs.*.value' => 'nullable|string|max:255',
        ]);

        // slug generation and uniqueness
        $slug = Str::slug($data['name']);
        $originalSlug = $slug;
        $i = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('images/ProductImages');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $imageUrl = '/images/ProductImages/' . $filename;
        }

        $product = Product::create([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'],
            'is_active' => $data['is_active'] ?? true,
            'image_url' => $imageUrl,
            'watt' => $data['watt'] ?? null,
            'brand' => $data['brand'] ?? null,
            'subkategori_product_id' => $data['subkategori_product_id'] ?? null,
            'specs' => $data['specs'] ?? null,
        ]);

        // --- FIX FOR FLUTTER APP ---
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product created successfully',
                'data' => $product
            ], 201);
        }

        return redirect()->route('admin.products')->with('success', 'Product created');
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'subkategori_product_id' => 'nullable|exists:subkategori_products,id',
            'watt' => 'nullable|integer',
            'brand' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:5120',
            'specs' => 'nullable|array',
            'specs.*.key' => 'nullable|string|max:255',
            'specs.*.value' => 'nullable|string|max:255',
        ]);

        if ($product->name !== $data['name']) {
            $slug = Str::slug($data['name']);
            $originalSlug = $slug;
            $i = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $i++;
            }
            $product->slug = $slug;
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('images/ProductImages');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);
            $product->image_url = '/images/ProductImages/' . $filename;
        }

        $product->name = $data['name'];
        $product->description = $data['description'] ?? null;
        $product->price = $data['price'];
        $product->stock = $data['stock'];
        $product->is_active = $data['is_active'] ?? true;
        $product->watt = $data['watt'] ?? null;
        $product->brand = $data['brand'] ?? null;
        $product->subkategori_product_id = $data['subkategori_product_id'] ?? null;
        $product->specs = $data['specs'] ?? null;
        $product->save();

        // --- FIX FOR FLUTTER APP ---
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully',
                'data' => $product
            ], 200);
        }

        return redirect()->route('admin.products')->with('success', 'Product updated');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Request $request, $id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            if ($request->expectsJson()) {
                 return response()->json(['message' => 'Product not found'], 404);
            }
            return redirect()->route('admin.products')->with('error', 'Product not found');
        }

        // Optionally remove image file
        // if($product->image_url && file_exists(public_path($product->image_url))) { ... }
        
        $product->delete();

        // Check if request expects JSON (API) or redirect (web)
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ]);
        }

        return redirect()->route('admin.products')->with('success', 'Product deleted');
    }
}
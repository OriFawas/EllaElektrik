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
        ]);

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
        $product->save();

        return redirect()->route('admin.products')->with('success', 'Product updated');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        // Optionally remove image file
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product deleted');
    }
}

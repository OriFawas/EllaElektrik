<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display the specified product by slug.
     */
    public function show(Product $product)
    {
        // Only show active products
        if (!$product->is_active) {
            abort(404);
        }

        // Eager-load relations for category context
        $product->load(['subkategori.kategori']);

        // Build a frontend-friendly payload
        $image = $product->image_url ?: '/images/placeholder.png';

        // Convert structured specs (key/value) into display strings
        $kvSpecs = [];
        if (is_array($product->specs)) {
            foreach ($product->specs as $row) {
                $k = isset($row['key']) ? trim((string) $row['key']) : '';
                $v = isset($row['value']) ? trim((string) $row['value']) : '';
                if ($k !== '' && $v !== '') {
                    $kvSpecs[] = $k . ': ' . $v;
                } elseif ($v !== '') {
                    $kvSpecs[] = $v;
                }
            }
        }

        $payload = [
            'id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->name,
            'price' => (float) $product->price,
            'priceFormatted' => number_format((float) $product->price, 0, ',', '.'),
            'description' => $product->description,
            'image' => $image,
            // No gallery table yet; default to main image
            'gallery' => array_values(array_filter([$image])),
            'specs' => array_values(array_filter(array_merge(
                $kvSpecs,
                [
                    $product->brand ? 'Brand: ' . $product->brand : null,
                    $product->watt ? 'Daya: ' . $product->watt . ' Watt' : null,
                ]
            ))),
            'stock' => (int) $product->stock,
            'isActive' => (bool) $product->is_active,
            'watt' => $product->watt,
            'brand' => $product->brand,
            'category' => optional(optional($product->subkategori)->kategori)->name,
            'subcategory' => optional($product->subkategori)->name,
        ];

        return Inertia::render('DetailProduct', [
            'product' => $payload,
        ]);
    }
}

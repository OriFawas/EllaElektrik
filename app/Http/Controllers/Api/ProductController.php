<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Return products filtered by subcategory IDs.
     * Query param: subcategories=1,2,3
     */
    public function index(Request $request)
    {
        $subcats = $request->query('subcategories');

        $query = Product::query()->with('subkategori')->where('is_active', true);

        if ($subcats) {
            $ids = array_filter(array_map('intval', explode(',', $subcats)), fn($v) => $v > 0);
            if (!empty($ids)) {
                $query->whereIn('subkategori_product_id', $ids);
            }
        }

        $products = $query->orderBy('name')->get([
            'id', 'name', 'slug', 'price', 'stock', 'image_url', 'brand', 'watt', 'subkategori_product_id'
        ]);

        return response()->json($products);
    }
}

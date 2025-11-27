<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubkategoriProduct;
use App\Models\KategoriProduct;

class SubkategoriController extends Controller
{
    /**
     * Return subcategories based on category_id
     */
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $categoryName = $request->query('category');

        $query = SubkategoriProduct::query();

        if ($categoryId) {
            $query->where('kategori_product_id', $categoryId);
        }

        // Filter by category name
        if ($categoryName) {
            $kategori = KategoriProduct::where('name', $categoryName)->first();
            if ($kategori) {
                $query->where('kategori_product_id', $kategori->id);
            }
        }

        $subs = $query->orderBy('name')->get(['id', 'name', 'slug', 'kategori_product_id']);

        return response()->json($subs);
    }
}

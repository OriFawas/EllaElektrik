<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubkategoriProduct;
use App\Models\KategoriProduct;

class SubkategoriController extends Controller
{
    /**
     * Return subcategories. Optionally filter by category name or slug via ?category=...
     */
    public function index(Request $request)
    {
        $category = $request->query('category');

        $query = SubkategoriProduct::query();

        if ($category) {
            // try to find category by slug or name
            $kategori = KategoriProduct::where('slug', $category)
                ->orWhere('name', $category)
                ->first();

            if ($kategori) {
                $query->where('kategori_product_id', $kategori->id);
            } else {
                // if category not found, return empty
                return response()->json([]);
            }
        }

        $subs = $query->orderBy('name')->get(['id', 'name', 'slug', 'kategori_product_id']);

        return response()->json($subs);
    }
}

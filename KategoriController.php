<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduct;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Return all categories
     */
    public function index()
    {
        $categories = KategoriProduct::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'description'])
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'description' => $cat->description,
                ];
            });

        return response()->json($categories);
    }
}

<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\KategoriProduct;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function index($category = null)
    {
        // Jika tidak ada kategori, redirect ke kategori pertama atau tampilkan halaman kosong
        if (!$category) {
            $firstCategory = KategoriProduct::orderBy('name')->first();
            if ($firstCategory) {
                return redirect('/shop/' . $firstCategory->slug);
            }
            return Inertia::render('ShopPage', [
                'category' => null,
                'categoryId' => null,
            ]);
        }

        // Cari kategori berdasarkan slug
        $categoryModel = KategoriProduct::where('slug', $category)->first();
        
        if (!$categoryModel) {
            // Jika tidak ada kategori dengan slug tersebut, redirect ke homepage atau tampilkan error
            return redirect('/');
        }

        return Inertia::render('ShopPage', [
            'category' => $categoryModel->name,
            'categoryId' => $categoryModel->id,
            'categorySlug' => $categoryModel->slug,
        ]);
    }
}

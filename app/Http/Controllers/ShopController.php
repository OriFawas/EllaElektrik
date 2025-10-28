<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class ShopController extends Controller
{
    public function index()
    {
        return Inertia::render('ShopPage');
    }

    public function showByCategory($category)
    {
        return Inertia::render('ShopPage', [
            'category' => $category
        ]);
    }
}

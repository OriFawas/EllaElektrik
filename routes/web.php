<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Product;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController as PublicProductController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Update this dashboard route - add 'admin' to middleware
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard'); // Added 'admin' here

// Admin dashboard (utama)
Route::get('/admin/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('admin.dashboard');

// Admin produk
use App\Models\KategoriProduct;
use App\Http\Controllers\Admin\ProductController;

Route::get('/admin/products', function () {
    $products = Product::with(['subkategori.kategori'])->latest()->get();
    $categories = KategoriProduct::with('subkategories')->get();

    return Inertia::render('Products', [
        'products' => $products,
        'categories' => $categories,
    ]);
})->middleware(['auth', 'verified', 'admin'])->name('admin.products');

// Product CRUD (store / update / destroy)
Route::post('/admin/products', [ProductController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.products.store');

Route::put('/admin/products/{product}', [ProductController::class, 'update'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.products.update');

Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.products.destroy');

// Halaman Shop (user)
Route::get('/shop/{category}', [ShopController::class, 'index'])
    ->name('shop');

    // Halaman Compare Produk (user)
    Route::get('/compare-products', function () {
        $products = Product::with(['subkategori'])
            ->where('is_active', true)
            ->latest()
            ->get()
            ->map(function ($p) {
                $image = $p->image_url ?: '/images/placeholder.png';
                // Build specs from JSON KV + brand/watt
                $kvSpecs = [];
                if (is_array($p->specs)) {
                    foreach ($p->specs as $row) {
                        $k = isset($row['key']) ? trim((string) $row['key']) : '';
                        $v = isset($row['value']) ? trim((string) $row['value']) : '';
                        if ($k !== '' && $v !== '') {
                            $kvSpecs[] = $k . ': ' . $v;
                        } elseif ($v !== '') {
                            $kvSpecs[] = $v;
                        }
                    }
                }
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => (float) $p->price,
                    'image' => $image,
                    'specs' => array_values(array_filter(array_merge($kvSpecs, [
                        $p->brand ? 'Brand: ' . $p->brand : null,
                        $p->watt ? 'Daya: ' . $p->watt . ' Watt' : null,
                    ]))),
                    'subkategori_id' => $p->subkategori_product_id,
                    'subkategori_name' => optional($p->subkategori)->name,
                ];
            });

        return Inertia::render('CompareProducts', [
            'products' => $products,
        ]);
    })->name('compare.products');

Route::get('/detailproduk', function () {
    return Inertia::render('DetailProduct');
});

// Public Product Detail by slug
Route::get('/products/{product:slug}', [PublicProductController::class, 'show'])
    ->name('products.show');


// About Us
Route::get('/about', function () {
    return Inertia::render('About');
});

// Help Center
Route::get('/help-center', function () {
    return Inertia::render('HelpCenter');
})->name('help-center');

// Garansi
Route::get('/garansi', function () {
    return Inertia::render('Garansi');
})->name('garansi');

// Contact Us
Route::get('/contact', function () {
    return Inertia::render('ContactUs');
})->name('contact');

    // Halaman Keranjang
    Route::get('/cart', function () {
        return Inertia::render('CartPage');
    })->name('cart');

    // Halaman Checkout
    Route::get('/checkout', function () {
        return Inertia::render('CheckoutPage');
    })->name('checkout');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
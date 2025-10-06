<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Update this dashboard route - add 'admin' to middleware
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard'); // Added 'admin' here

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
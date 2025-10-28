<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function showDataDiri()
{
    return Inertia::render('DataDiri');
}
}
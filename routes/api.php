<?php

use App\Application\Product\Controllers\ProductSearchController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductSearchController::class, 'search'])->name('products');


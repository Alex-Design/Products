<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Get
Route::get('/product/{id}', [ProductController::class, 'getProductById']);
Route::get('/product/{code}/{locale}', [ProductController::class, 'getProductByCodeAndLocale']);

// Create
Route::post('/product', [ProductController::class, 'createProduct']);

// Update
Route::put('/product/{id}', [ProductController::class, 'updateProductById']);

// Delete
Route::delete('/product/{id}', [ProductController::class, 'deleteProductById']);

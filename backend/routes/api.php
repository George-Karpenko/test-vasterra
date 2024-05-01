<?php

use App\Http\Controllers\ReturnOfProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/return-of-product', [ReturnOfProductController::class, 'index']);
Route::post('/return-of-product', [ReturnOfProductController::class, 'store']);
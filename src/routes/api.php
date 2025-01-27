<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/index', function () {
    return ["message" => "Hello, World!"];
});
 
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('items')->group(function () {
    Route::get('/', [ItemController::class, 'index']);     // Get all items or filter by category
    Route::post('/', [ItemController::class, 'store']);    // Store a new item
    Route::get('{id}', [ItemController::class, 'get']);     // Get a specific item by ID
    Route::delete('{id}', [ItemController::class, 'delete']); // Delete a specific item by ID
});

Route::get('recent', [ItemController::class, 'recent']);


Route::post('/upload', [FileController::class, 'upload']);
Route::post('/multi_upload', [FileController::class, 'uploadMultiple']);
Route::get('/images', [FileController::class, 'getAllFiles']);
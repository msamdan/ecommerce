<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\OrderController;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\BasketController;
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

Route::get('/login', [AuthController::class, 'unauthorized'])->name('login');

Route::prefix('order')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [OrderController::class, 'list']);
    Route::post('/', [OrderController::class, 'create']);
    Route::get('/{id}', [OrderController::class, 'get']);
    Route::put('/{id}', [OrderController::class, 'update']);
    Route::patch('/{id}', [OrderController::class, 'update']);
    Route::delete('/{id}', [OrderController::class, 'delete']);
});

Route::prefix('basket')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [BasketController::class, 'get']);
    Route::get('/discount', [BasketController::class, 'discount']);
    Route::get('/item', [BasketController::class, 'listItems']);
    Route::post('/item', [BasketController::class, 'addItem']);
    Route::put('/item/{id}', [BasketController::class, 'updateItem']);
    Route::patch('/item/{id}', [BasketController::class, 'updateItem']);
    Route::delete('/item/{id}', [BasketController::class, 'removeItem']);
});

Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('auth:sanctum');
    Route::get('/logout', [AuthController::class, 'logout']);
});

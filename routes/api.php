<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
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

Route::get('/unauthorized', function (Request $request) {
    return Controller::jsonResponse([], 401, "Invalid token");
})->name('unauthorized');

Route::post('/auth', [\App\Http\Controllers\UserController::class, 'create']);
Route::get('/get-token', [\App\Http\Controllers\UserController::class, 'confirm']);
Route::post('/update-record', [\App\Http\Controllers\UserController::class, 'update'])
    ->middleware('auth:api');
Route::get('/user', [\App\Http\Controllers\UserController::class, 'show'])
    ->middleware('auth:api');

Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index']);
Route::get('/product', [\App\Http\Controllers\ProductController::class, 'show']);

Route::post('/order', [\App\Http\Controllers\OrderController::class, 'create'])
    ->middleware('auth:api');
Route::get('/get-orders', [\App\Http\Controllers\OrderController::class, 'show'])
    ->middleware('auth:api');

Route::post('/add-to-favourites', [\App\Http\Controllers\FavouriteController::class, 'create'])
    ->middleware('auth:api');
Route::get('/get-favourites', [\App\Http\Controllers\FavouriteController::class, 'show'])
    ->middleware('auth:api');
Route::get('/delete-favourite', [\App\Http\Controllers\FavouriteController::class, 'delete'])
    ->middleware('auth:api');

Route::get('/city', [\App\Http\Controllers\CityController::class, 'index']);

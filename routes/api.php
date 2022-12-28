<?php

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\API\v1\Auth\AuthController;
use App\Http\Controllers\API\v1\BasicController;
use App\Http\Controllers\API\v1\Post\FavouriteController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::controller(AuthController::class)->prefix('user')->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'loginOne');
    });
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::controller(PostController::class)->prefix('post')->group(function () {
            Route::post('store', 'store')->name('store');
        });
        Route::controller(FavouriteController::class)->prefix('favourite')->group(function () {
            Route::post('store', 'store');
        });
    });

    Route::controller(BasicController::class)->group(function () {
        Route::prefix('sellers')->name('sellers.')->group(function () {
            Route::post('register', 'sellerRegister')->name('register');
            Route::post('details', 'sellerDetails')->name('details');
            Route::post('edit', 'sellerEdit')->name('edit');
        });
        Route::prefix('products')->name('products')->group(function () {
            Route::post('add', 'addProducts')->name('add');
            Route::post('sold', 'soldProduct')->name('sold');
        });
        Route::get('city', 'getCity')->name('city');
    });
});

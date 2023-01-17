<?php

namespace App;

use App\Http\Controllers\Admin\BannerPricingController;
use App\Http\Controllers\Admin\BasicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeaturedProductPricingController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReferSchemeController as AdminReferSchemeController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "raiyan";
});

Route::prefix('admin')->middleware(['web'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout-admin');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin', 'web'])->group(function () {
    Route::name('home.')->controller(DashboardController::class)->group(function () {
        Route::get('/', 'home')->name('index');
    });

    //for references
    // Route::name('users.')
    //     ->prefix('users')
    //     ->controller(UsersController::class)->group(function () {
    //     Route::get('/', 'index')->name('index');
    //     Route::get('blocked', 'index')->name('blocked');
    //     Route::get('deleted', 'index')->name('deleted');
    //     Route::post('store', 'store')->name('store');
    //     Route::get('{id}/edit', "edit")->name('edit');
    //     Route::delete('/{id}', 'destroy')->name('destroy');
    //     Route::post('update', 'update')->name('update');
    //     Route::put('status', 'status')->name('status');
    // });

    Route::name('sellers.')
        ->prefix('sellers')
        ->controller(SellerController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('blocked', 'index')->name('blocked');
        Route::get('deleted', 'index')->name('deleted');
        Route::post('store', 'store')->name('store');
        Route::get('{id}/edit', "edit")->name('edit');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('update', 'update')->name('update');
        Route::put('status', 'status')->name('status');
    });
    Route::name('products.')
        ->prefix('products')
        ->controller(ProductController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('blocked', 'index')->name('blocked');
        Route::get('deleted', 'index')->name('deleted');
        Route::post('store', 'store')->name('store');
        Route::get('{id}/edit', "edit")->name('edit');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('update', 'update')->name('update');
        Route::put('status', 'status')->name('status');
    });
    Route::name('memberships.')
        ->prefix('memberships')
        ->controller(MembershipController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('blocked', 'index')->name('blocked');
        Route::get('deleted', 'index')->name('deleted');
        Route::post('store', 'store')->name('store');
        Route::get('{id}/edit', "edit")->name('edit');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('update', 'update')->name('update');
        Route::put('status', 'status')->name('status');
    });
    Route::name('referscheme.')
        ->prefix('referscheme')
        ->controller(AdminReferSchemeController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('blocked', 'index')->name('blocked');
        Route::get('deleted', 'index')->name('deleted');
        Route::post('store', 'store')->name('store');
        Route::get('{id}/edit', "edit")->name('edit');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('update', 'update')->name('update');
        Route::put('status', 'status')->name('status');
    });
    Route::name('banner-pricing.')
        ->prefix('banner-pricing')
        ->controller(BannerPricingController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('blocked', 'index')->name('blocked');
        Route::get('deleted', 'index')->name('deleted');
        Route::post('store', 'store')->name('store');
        Route::get('{id}/edit', "edit")->name('edit');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('update', 'update')->name('update');
        Route::put('status', 'status')->name('status');
    });
    Route::name('featuredproduct-pricing.')
        ->prefix('featuredproduct-pricing')
        ->controller(FeaturedProductPricingController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('blocked', 'index')->name('blocked');
        Route::get('deleted', 'index')->name('deleted');
        Route::post('store', 'store')->name('store');
        Route::get('{id}/edit', "edit")->name('edit');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('update', 'update')->name('update');
        Route::put('status', 'status')->name('status');
    });
    Route::name('others.')->prefix('others')->controller(BasicController::class)->group(function () {
        Route::post('free-trial', 'storeFreeTrialPeriod')->name('free-trial');
        Route::post('welcome-bonus', 'storeWelcomeBonus')->name('welcome-bonus');
    });

    Route::name('extras.')->prefix('extras')->controller(BasicController::class)->group(function () {
        Route::post('storeOrUpdate', 'storeOrUpdate')->name('storeOrUpdate');
        Route::get('extrasPage', 'extrasPage')->name('extrasPage');
    });
});

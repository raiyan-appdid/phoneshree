<?php

namespace App;

use App\Http\Controllers\Admin\ActiveBannerAdsController;
use App\Http\Controllers\Admin\BannerPricingController;
use App\Http\Controllers\Admin\BasicController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeaturedProductController;
use App\Http\Controllers\Admin\FeaturedProductPricingController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PopUpController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReferSchemeController as AdminReferSchemeController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\SellPhoneByUserController;
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

    Route::name('banner-ads.')
        ->prefix('banner-ads')
        ->controller(ActiveBannerAdsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('status', 'status')->name('status');
    });

    Route::name('featured-product.')
        ->prefix('featured-product')
        ->controller(FeaturedProductController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('status', 'status')->name('status');
    });

    Route::prefix('notification')->name('notification.')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
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
        Route::post('assignMembership', 'assignMembership')->name('assignMembership');
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
        Route::post('get-city', 'get_cities')->name('get-city');
        Route::post('get-area', 'get_areas')->name('get-area');
        Route::get('wallet', 'walletIndex')->name('wallet');
        Route::post('getWalletData', 'getWalletData')->name('getWalletData');
        Route::post('storeWalletData', 'storeWalletData')->name('storeWalletData');
    });

    Route::name('extras.')->prefix('extras')->controller(BasicController::class)->group(function () {
        Route::post('storeOrUpdate', 'storeOrUpdate')->name('storeOrUpdate');
        Route::get('extrasPage', 'extrasPage')->name('extrasPage');
    });

    Route::name('popup.')
        ->prefix('popup')
        ->controller(PopUpController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('blocked', 'index')->name('blocked');
        Route::get('deleted', 'index')->name('deleted');
        Route::post('store', 'store')->name('store');
        Route::get('{id}/edit', "edit")->name('edit');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('update', 'update')->name('update');
        Route::put('status', 'status')->name('status');
    });
    Route::name('brands.')
        ->prefix('brands')
        ->controller(BrandController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('blocked', 'index')->name('blocked');
        Route::get('deleted', 'index')->name('deleted');
        Route::post('store', 'store')->name('store');
        Route::get('{id}/edit', "edit")->name('edit');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('update', 'update')->name('update');
        Route::put('status', 'status')->name('status');
    });

    Route::prefix('sellPhoneByUser')->name('sellPhoneByUser.')->controller(SellPhoneByUserController::class)->group(function () {
        // Route::post('store', 'store')->name('store');
        Route::get('/', 'index')->name('index');
    });

});

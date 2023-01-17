<?php

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\API\v1\Auth\AuthController;
use App\Http\Controllers\API\v1\BannerAdsTransactionController;
use App\Http\Controllers\API\v1\BasicController;
use App\Http\Controllers\API\v1\FeaturedProductTransactionController;
use App\Http\Controllers\API\v1\Post\FavouriteController;
use App\Http\Controllers\API\v1\ProductController;
use App\Http\Controllers\API\v1\RazorpayOrderController;
use App\Http\Controllers\API\v1\SellerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::group(['prefix' => 'get'], function () {
        Route::get('states', [BasicController::class, 'get_states']);
        Route::post('cities', [BasicController::class, 'get_cities']);
    });
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

    Route::controller(SellerController::class)->group(function () {
        Route::prefix('sellers')->name('sellers.')->group(function () {
            Route::post('register', 'sellerRegister')->name('register');
            Route::post('login', 'sellerLogIn')->name('login');
            Route::post('details', 'sellerDetails')->name('details');
            Route::post('edit', 'sellerEdit')->name('edit');
        });
    });

    Route::controller(ProductController::class)->group(function () {
        Route::prefix('products')->name('products')->group(function () {
            Route::post('add', 'addProducts')->name('add');
            Route::post('sold', 'soldProduct')->name('sold');
            Route::post('status', 'product_status')->name('status');
            Route::post('golive', 'productToLive')->name('golive');
            Route::post('toinventory', 'productToInvetory')->name('toinventory');
            Route::post('getliveproducts', 'getLiveProducts')->name('getliveproducts');
            Route::post('getproduct', 'getProduct')->name('getproduct');
            Route::post('getinventoryproducts', 'getInventoryProducts')->name('getinventoryproducts');
            Route::post('getsoldproducts', 'getsoldproducts')->name('getsoldproducts');
        });
    });

    Route::controller(BasicController::class)->group(function () {
        Route::post('checkreferralcode', 'checkReferralCode')->name('checkreferralcode');
        Route::get('getbannerpricing', 'getBannerPricing')->name('getbannerpricing');
        Route::get('getfeaturedproductpricing', 'getFeaturedProductPricing')->name('getfeaturedproductpricing');
        Route::post('getbalanceandtransactionlist', 'getBalanceAndTransactionList')->name('getbalanceandtransactionlist');
        Route::post('getsellerfromactivebannerlist', 'getSellerFromActiveBannerList')->name('getsellerfromactivebannerlist');
        Route::post('loadwallet', 'loadWallet')->name('loadwallet');
        Route::post('getmembershiplist', 'getMembershipList')->name('getmembershiplist');
        Route::post('getmembershiptransactionlist', 'getMembershipTransactionList')->name('getmembershiptransactionlist');
        Route::get('referralschemesetup', 'referralSchemeSetup')->name('referralschemesetup');
        Route::get('extras', 'extras')->name('extras');
    });

    Route::prefix('featured-products')->name('featured-products.')->controller(FeaturedProductTransactionController::class)->group(function () {
        Route::post('store', 'store')->name('store');
        Route::post('transactionlist', 'transactionList')->name('transactionlist');
    });

    Route::prefix('banner-ads')->name('banner-ads.')->controller(BannerAdsTransactionController::class)->group(function () {
        Route::post('store', 'store')->name('store');
        Route::post('transactionlist', 'transactionList')->name('transactionlist');
    });

    Route::prefix('buyer')->name('buyer.')->group(function () {
        Route::post('getbuyerdashboard', [BasicController::class, 'getBuyerDashboard'])->name('getbuyerdashboard');
    });

    Route::prefix('razorpay')->name('razorpay.')->controller(RazorpayOrderController::class)->group(function () {
        Route::post('create', 'create')->name('create');
        Route::post('fetch_order', 'fetch_order')->name('fetch_order');
    });
});

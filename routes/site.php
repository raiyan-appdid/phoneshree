<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('about', 'about');
    Route::get('contact', 'contact');
});

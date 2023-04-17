<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    HomeController,
    ContactController,
};

// pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/medicine-detail/{medicine:slug}', [HomeController::class, 'medicineDetail'])->name('medicineDetail');
Route::post('/create-order/{medicine}', [HomeController::class, 'createOrder'])->name('createOrder');
Route::view('/about', "frontend.pages.about")->name('about');
Route::view('/coming-soon', "frontend.pages.coming-soon")->name('comingSoon');

// contact
Route::resource('contacts', ContactController::class)->only(['index', 'store']);


//Language Translation
Route::get('index/{locale}', [HomeController::class, 'lang']);

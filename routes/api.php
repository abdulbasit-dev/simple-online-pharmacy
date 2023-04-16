<?php

use Illuminate\Support\Facades\Route;

//API V1
Route::group(['prefix' => 'v1'], function () {

    /*=================
       PUBLIC ROUTES
    =================*/

    //Clear all cache
    Route::get('/clear-all-cache', function () {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('route:clear');
        Artisan::call('clear-compiled');

        return "cache cleared";
    });

    //reset database
    Route::get('/reset-db', function () {
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
        return "database rested with seeded data.";
    });
});

// CAllBACK ROUTES
Route::fallback(function () {
    return response()->json([
        'result' => false,
        'status' => 404,
        'message' => "invalid route",
    ], 404);
});

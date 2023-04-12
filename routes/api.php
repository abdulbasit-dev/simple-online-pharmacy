<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    OrderController,
    QrCheckController,
    FastpayPaymentController,
    FibPaymentController
};

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

    //FastPay
    Route::prefix('fastpay')->middleware("fastpay")->group(function () {
        Route::post('/check-quantity', [OrderController::class, 'checkQuantity']);
        Route::post('/order-ticket', [OrderController::class, 'orderTicket']);
        Route::post('/get-order', [OrderController::class, 'getOrder']);
    });

    //Card Selling
    Route::prefix('card-selling')->middleware("cardSelling")->group(function () {
        Route::post('/check-quantity', [OrderController::class, 'checkQuantity']);
        Route::post('/order-ticket', [OrderController::class, 'orderTicket']);
        Route::post('/get-order', [OrderController::class, 'getOrder']);
    });

    Route::middleware("mobileApiKey")->group(function () {
        // check qr code
        Route::post('/check-qrcode', [QrCheckController::class, 'checkQrCode']);
        Route::post('/manual-check', [QrCheckController::class, 'manualCheck']);
    });


    Route::group(["prefix" => "payment", "as" => "payment."], function () {

        // PGW => Payment Gateway
        // fastpay PGW
        Route::group(["prefix" => "fastpay", "as" => "fastpay."], function () {
            Route::get('initiation/{orderId}', [FastpayPaymentController::class, 'initiation'])->name('initiation');
            Route::post('callback-payment', [FastpayPaymentController::class, 'callbackPayment'])->name('callbackPayment');
        });

        // FIB PGW
        Route::group(["prefix" => "fib", "as" => "fib."], function () {
            // Route::get('authorization', [FibPaymentController::class, 'authorization'])->name('authorization');
            Route::get('authorization/{orders}', [FibPaymentController::class, 'authorization'])->name('authorization');
            Route::post('callback', [FibPaymentController::class, 'callback'])->name('callback');
            Route::post('check-status', [FibPaymentController::class, 'checkStatus'])->name('checkStatus');
        });
    });

    /*=================
      PROTECTED ROUTES
    ===================*/
    Route::group(['middleware' => ['auth:sanctum']], function () {
    });
});

//API V2
Route::group(['prefix' => 'v2'], function () {
    //FastPay
    Route::prefix('fastpay')->middleware("fastpay")->group(function () {
        Route::post('/check-quantity', [OrderController::class, 'checkQuantity']);
        Route::post('/order-ticket', [OrderController::class, 'fastpayOrderTicket']);
        Route::post('/get-order', [OrderController::class, 'getOrder']);
    });

    //Card Selling
    Route::prefix('card-selling')->middleware("cardSelling")->group(function () {
        Route::post('/check-quantity', [App\Http\Controllers\Api\V2\OrderController::class, 'checkQuantity']);
        Route::post('/order-ticket', [App\Http\Controllers\Api\V2\OrderController::class, 'cardSellingOrderTicket']);
        Route::post('/get-order', [App\Http\Controllers\Api\V2\OrderController::class, 'getOrder']);
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

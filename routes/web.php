<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    HomeController,
    ContactController,
    CheckoutController,
    MatchTicketController,
    SeasonTicketController,
    StripePaymentController,
    SwishPaymentController,
};

// pages
// Route::get('/', [HomeController::class, 'index'])->name('index');
Route::redirect('/', '/admin', 301)->name('index');
Route::view('/about', "frontend.pages.about")->name('about');
Route::view('/coming-soon', "frontend.pages.coming-soon")->name('comingSoon');

// contact
Route::resource('contacts', ContactController::class)->only(['index', 'store']);

// season ticket
Route::group(["prefix" => "season-ticket"], function () {
    Route::get('', [SeasonTicketController::class, 'seasonTicket'])->name('seasonTicket');
    Route::post('store', [SeasonTicketController::class, 'seasonTicketStore'])->name('seasonTicketStore');
    Route::get('get-ticket-price', [SeasonTicketController::class, 'getTicketPrice'])->name('getTicketPrice');
    Route::get('check-ticket-quantity', [SeasonTicketController::class, 'checkTicketQuantity'])->name('checkTicketQuantity');
});

// match tickets
Route::group(["prefix" => "match-ticket", "as" => "match-ticket."], function () {
    Route::get('', [MatchTicketController::class, 'index'])->name('index');
    Route::post('', [MatchTicketController::class, 'store'])->name('store');
    Route::get('get-ticket-price', [MatchTicketController::class, 'getTicketPrice'])->name('getTicketPrice');
    Route::get('get-sections', [MatchTicketController::class, 'getSections'])->name('getSections');
    Route::get('check-ticket-quantity', [MatchTicketController::class, 'checkTicketQuantity'])->name('checkTicketQuantity');
});

//checkout
Route::get('checkout', [CheckoutController::class, 'checkout'])->name('checkout.index');
Route::post('checkout/create-order', [CheckoutController::class, 'createOrder'])->name('checkout.createOrder');
// Route::post('checkout/swish-payment', [CheckoutController::class, 'swishPayment'])->name('checkout.swishPayment');

// Payment
Route::group(["prefix" => "payment", "as" => "payment."], function () {
    //swish payment
    Route::group(["prefix" => "swish", "as" => "swish."], function () {
        Route::get('payment-request/{orderId}', [SwishPaymentController::class, 'paymentRequest'])->name('paymentRequest');
        Route::post('callback', [SwishPaymentController::class, 'callback'])->name('callback');
    });

    //stripe payment
    Route::group(["prefix" => "stripe", "as" => "stripe."], function () {
        Route::get('create-checkout-session', [StripePaymentController::class, 'createSession'])->name('createSession');
        Route::post('webhook', [StripePaymentController::class, 'webhook'])->name('webhook');
        Route::get('success', [StripePaymentController::class, 'success'])->name('success');
        Route::get('cancel', [StripePaymentController::class, 'cancel'])->name('cancel');
    });

    Route::get('success', [CheckoutController::class, 'success'])->name('success');
    Route::get('cancel', [CheckoutController::class, 'cancel'])->name('cancel');
    // Route::get('cancel', [CheckoutController::class, 'cancel'])->name('cancel');
    Route::get('download-ticket/{orderId}', [CheckoutController::class, 'ticketDownload'])->name('ticketDownload');
});

//Language Translation
Route::get('index/{locale}', [HomeController::class, 'lang']);

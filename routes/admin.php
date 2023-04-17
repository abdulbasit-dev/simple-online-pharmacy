<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    HomeController,
    OriginController,
    OrderController,
    ProfileController,
    UserController,
    SidebarControler,
    MedicineController,
    TypeController,
};

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

//Admin Routes
Route::group(['middleware' => ['auth'], 'as' => 'admin.'], function () {
    // HomeController Routes
    Route::get('/', [HomeController::class, 'index'])->name('index');

    //profile
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // store and delete file
    Route::post('/store-temp-file', [HomeController::class, 'storeTempFile'])->name('storeTempFile');
    Route::post('/delete-temp-file', [HomeController::class, 'deleteTempFile'])->name('deleteTempFile');

    //notifications
    Route::post('/mark-as-read', [HomeController::class, 'markNotification'])->name('markNotification');

    //sidebar counter
    Route::get('/medicine-status-count', [SidebarControler::class, 'getMedicineStatusCount'])->name('getMedicineStatusCount');
    Route::get('/order-status-count', [SidebarControler::class, 'orderStatusCount'])->name('orderStatusCount');

    //origins
    Route::resource("origins", OriginController::class)->except(['show']);

    //origins
    Route::resource("types", TypeController::class)->except(['show']);

    // medicines
    Route::resource("medicines", MedicineController::class);

    // orders
    Route::resource("orders", OrderController::class)->only(["index", 'show']);
    Route::post('orders/{order}/change-status', [OrderController::class, 'changeStatus'])->name('orders.changeStatus');

    //users
    Route::resource("users", UserController::class);
});

<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    HomeController,
    LaravelExcelExport,
    OriginController,
    OrderController,
    ProfileController,
    RoleController,
    UserController,
    SettingController,
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
    Route::get('/get-match-data', [HomeController::class, 'matchData'])->name('matchData');

    // SANDBOX ROUTES
    Route::group(['prefix' => 'sandbox', 'as' => 'sandbox.'], function () {

    });

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

    //export template for models by passing file type and table name
    Route::get('export-template/{type}/{table}', LaravelExcelExport::class)->name('exportTemplate');

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

    //roles
    Route::resource("roles", RoleController::class);

    //settings
    Route::resource("settings", SettingController::class)->except(['show']);

    //Language Translation
    Route::get('index/{locale}', [HomeController::class, 'lang']);
});

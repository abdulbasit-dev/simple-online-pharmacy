<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    AgeGroupController,
    BannerController,
    CategoryController,
    ContactController,
    MatchController,
    HomeController,
    LaravelExcelExport,
    OriginController,
    OrderController,
    ProfileController,
    RoleController,
    SeatController,
    UserController,
    SettingController,
    SidebarControler,
    TeamController,
    TicketController,
    SandboxController,
    GateController,
    LocalOrderController,
    MatchOrderController,
    MatchTicketController,
    MedicineController,
    PaymentMethodController,
    ReportController,
    SeasonOrderController,
    SeasonTicketController,
    SectionController,
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
        Route::get('/qrcode-viewer', [SandboxController::class, 'qrcodeViewer'])->name('qrcodeViewer');
        Route::get('/stadium-map', [SandboxController::class, 'stadiumMap'])->name('stadiumMap');
        Route::get('/ticket-invoice', [SandboxController::class, 'ticketInvoice'])->name('invoice');
        Route::get('/ticket-invoice-pdf', [SandboxController::class, 'ticketInvoicePdf'])->name('invoicePdf');

        Route::group(['prefix' => 'mail', 'as' => 'mail.'], function () {
            Route::get('/render-season-ticket', [SandboxController::class, 'renderSeasonTicket'])->name('renderSeasonTicket');
            Route::get('/send-season-ticket', [SandboxController::class, 'sendSeasonTicket'])->name('sendSeasonTicket');
        });
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

    //get count of matches
    Route::get('/match-status-count', [SidebarControler::class, 'matchStatusCount'])->name('matchStatusCount');
    Route::get('/ticket-status-count', [SidebarControler::class, 'ticketStatusCount'])->name('ticketStatusCount');
    Route::get('/order-status-count', [SidebarControler::class, 'orderStatusCount'])->name('orderStatusCount');

    //export template for models by passing file type and table name
    Route::get('export-template/{type}/{table}', LaravelExcelExport::class)->name('exportTemplate');

    // payment methods
    Route::post('/payment-methods/change-status/{paymentMethod}', [PaymentMethodController::class, 'changeStatus'])->name('paymentMethods.changeStatus');
    Route::resource("payment-methods", PaymentMethodController::class, [
        "names" => "paymentMethods"
    ])->except(['show']);

    //categories
    Route::resource("categories", CategoryController::class)->except(['show']);

    // age groups
    Route::resource("age-groups", AgeGroupController::class, [
        "names" => "ageGroups"
    ])->except(['show']);

    //seats
    Route::get('/seats/seat-by-category/{categoryId}', [SeatController::class, 'getSeatsByCategory'])->name('seats.getSeatsByCategory');
    Route::resource("seats", SeatController::class);

    //gates
    Route::get('/gates/gate-by-category/{categoryId}', [GateController::class, 'getGatesByCategory'])->name('gates.getGatesByCategory');
    Route::get('/gates/gate-limit/{gate}', [GateController::class, 'getGateLimit'])->name('gates.getGateLimit');
    Route::resource("gates", GateController::class)->except(['show']);

    // sections
    Route::post('/sections/change-status/{section}', [SectionController::class, 'changeStatus'])->name('sections.changeStatus');
    Route::resource("sections", SectionController::class)->except(['show']);

    //contacts
    Route::resource("contacts", ContactController::class)->only(["index", 'show', "destroy"]);

    //teams
    Route::resource("teams", TeamController::class)->except(['show']);

    //matches
    Route::resource("matches", MatchController::class)->except(['show']);

    //origins
    Route::resource("origins", OriginController::class)->except(['show']);

    //origins
    Route::resource("types", TypeController::class)->except(['show']);

    // medicines
    Route::resource("medicines", MedicineController::class);

    //tickets
    Route::get('/tickets/list', [TicketController::class, 'list'])->name('tickets.list');
    Route::resource("tickets", TicketController::class);

    //season tickets
    Route::resource("season-tickets", SeasonTicketController::class, [
        "names" => "seasonTickets"
    ]);

    // match tickets
    Route::resource("match-tickets", MatchTicketController::class, [
        "names" => "matchTickets"
    ]);

    //orders (match order)
    Route::resource("orders", OrderController::class)->only(["index", 'show']);

    //season orders
    Route::resource("season-orders", SeasonOrderController::class)->only(["index", 'show']);

    //match orders
    Route::resource("match-orders", MatchOrderController::class)->only(["index", 'show']);

    //local orders
    Route::get('/local-orders/download-ticket/{orderId}', [LocalOrderController::class, 'ticketDownload'])->name('local-orders.ticketDownload');
    Route::get('/local-orders/send-email/{orderId}', [LocalOrderController::class, 'sendEmail'])->name('local-orders.sendEmail');
    Route::get('/local-orders/get-tickets', [LocalOrderController::class, 'getTickets'])->name('local-orders.getTickets');
    Route::resource("local-orders", LocalOrderController::class);

    //users
    Route::resource("users", UserController::class);

    //roles
    Route::resource("roles", RoleController::class);

    //settings
    Route::resource("settings", SettingController::class)->except(['show']);

    // banners
    Route::post('/banners/change-status/{banner}', [BannerController::class, 'changeStatus'])->name('banners.changeStatus');
    Route::resource("banners", BannerController::class)->except(['show']);

    //reports
    Route::group(["prefix" => 'reports', 'as' => 'reports.'], function () {
        Route::get('gate', [ReportController::class, 'gate'])->name('gate');
    });

    //Language Translation
    Route::get('index/{locale}', [HomeController::class, 'lang']);
});

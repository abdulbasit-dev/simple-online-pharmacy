<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Order;
use Illuminate\Support\Carbon;

class SidebarControler extends Controller
{
    protected $desc = "This controller is for sidebar, return the count or each section. eg order statuses";

    public function getMedicineStatusCount()
    {
        $data = [
            "totalMedicine" => Medicine::count(),
            "expiredMedicine" => Medicine::where("expire_at", "<", Carbon::now())->count(),
        ];

        return response()->json($data);
    }

    public function orderStatusCount()
    {
        $data = [
            "totalOrders" => Order::count(),
            "pendingOrders" =>Order::where("status", OrderStatus::PENDING)->count(),
            "acceptedOrders" => Order::where("status", OrderStatus::ACCEPTED)->count(),
            "canceledOrders" => Order::where("status", OrderStatus::CANCELED)->count(),
        ];

        return response()->json($data);
    }
}

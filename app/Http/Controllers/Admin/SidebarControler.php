<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Carbon;

class SidebarControler extends Controller
{
    protected $desc = "This controller is for sidebar, return the count or each section. eg order statuses";

    public function matchStatusCount()
    {
        $data = [
            "totalMatches" => Game::count(),
            "todayMatches" => Game::whereDate('match_time', Carbon::today())->count(),
            "upcomingMatches" => Game::whereDate('match_time', '>', Carbon::today())->count(),
            "finishedMatches" => Game::whereDate('match_time', '<', Carbon::today())->count(),
        ];

        return response()->json($data);
    }

    public function ticketStatusCount()
    {
        $data = [
            "totalTickets" => Ticket::count(),
            "currentMatcheTickets" => Ticket::where('expire_at', '>', now())->count(),
            "expiredTickets" => Ticket::where('expire_at', '<', now())->count(),
        ];

        return response()->json($data);
    }

    public function orderStatusCount()
    {
        $data = [
            "totalOrders" => Order::count(),
            "currentMatcheOrders" => Order::where('expire_at', '>', now())->count(),
            "expiredOrders" => Order::where('expire_at', '<', now())->count(),
        ];

        return response()->json($data);
    }
}

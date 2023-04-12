<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Game;
use App\Models\Gate;
use App\Models\Store;
use App\Models\Ticket;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        //check permission
        $this->authorize("order_view");

        if ($request->ajax()) {
            $data = Order::query()
                ->with("ticket:id,seat_id,category_id", "ticket.category:id,name", "store:id,name")
                ->when($request->status, function ($query) use ($request) {
                    switch ($request->status) {
                        case 'current-match-order':
                            return $query->where('expire_at', '>=', now());
                            break;

                        case 'expired-order':
                            return $query->where('expire_at', '<', now());
                            break;
                        default:
                            return $query;
                            break;
                    }
                })
                ->when($request->matchId, function ($query) use ($request) {
                    return $query->where('match_id', $request->matchId);
                })
                ->when($request->gateNo, function ($query) use ($request) {
                    return $query->where('gate_no', $request->gateNo);
                })
                ->when($request->store, function ($query) use ($request) {
                    return $query->where('store_id', $request->store);
                })
                ->when($request->ticketNumber, function ($query) use ($request) {
                    return $query->where('ticket_number', "like", "%" . $request->ticketNumber . "%");
                })
                ->when($request->serialNumber, function ($query) use ($request) {
                    return $query->where('serial_number', "like", "%" . $request->serialNumber . "%");
                })
                ->when($request->transactionId, function ($query) use ($request) {
                    return $query->where('transaction_id', "like", "%" . $request->transactionId . "%");
                })
                ->when($request->used, function ($query) use ($request) {
                    if ($request->used == "used") {
                        return $query->where('is_used', true);
                    } else {
                        return $query->where('is_used', false);
                    }
                })
                ->when($request->expired, function ($query) use ($request) {
                    if ($request->expired == "expired") {
                        return $query->where('expire_at', '<', now());
                    } else {
                        return $query->where('expire_at', '>=', now());
                    }
                })
                ->when($request->categoryId, function ($query) use ($request) {
                    return $query->whereHas('ticket', function ($query) use ($request) {
                        return $query->where('category_id', $request->categoryId);
                    });
                });
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="d-flex justify-content-center">';
                    if (auth()->user()->can("order_view"))
                        $td .= '<a href="' . route('admin.orders.show', $row->id) . '" type="button" class="btn btn-sm btn-primary waves-effect waves-light me-1">' . __('buttons.view') . '</a>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->editColumn("store_id", function ($row) {
                    if (strtolower($row->store->name) == "fastpay") {
                        return '<span class="badge badge-pill badge-fastpay font-size-13 p-2">' . ucfirst($row->store->name) . '</span>';
                    } elseif (strtolower($row->store->name) == "website") {
                        return '<span class="badge badge-pill badge-website font-size-13 p-2">' . ucfirst($row->store->name) . '</span>';
                    }
                    // card selling
                    return '<span class="badge badge-pill badge-card-selling font-size-13 p-2">' . Str::title(str_replace("-", " ", $row->store->name)) . '</span>';
                })
                ->editColumn("is_used", function ($row) {
                    if ($row->is_used) {
                        return '<span class="badge badge-pill badge-soft-info font-size-13 p-2">' . __("translation.yes") . '</span>';
                    }
                    return '<span class="badge badge-pill badge-soft-danger font-size-13 p-2">' . __("translation.no") . '</span>';
                })
                ->editColumn("is_expired", function ($row) {
                    if ($row->expire_at < now()) {
                        return '<span class="badge badge-pill badge-soft-danger font-size-13 p-2">' . __("translation.yes") . '</span>';
                    } else {
                        return '<span class="badge badge-pill badge-soft-success font-size-13 p-2">' . __("translation.no") . '</span>';
                    }
                })
                ->editColumn("payment_status", function ($row) {
                    if ($row->payment_status == 1) {
                        return '<span class="badge badge-pill badge-soft-success font-size-13 p-2">Paid</span>';
                    } else {
                        return '<span class="badge badge-pill badge-soft-danger font-size-13 p-2">UnPaid</span>';
                        // return '<span class="badge badge-pill badge-soft-danger font-size-13 p-2">' . $row->payment_status . '</span>';
                    }
                })
                ->addColumn("gate_no", function ($row) {
                    if ($row->gate_no) {
                        return  '<span class="badge badge-pill badge-soft-info font-size-13 px-3 py-2 ">' . $row->gate_no . '</span>';
                    } else {
                        return  '<span class="badge badge-pill badge-soft-info font-size-13 px-3 py-2 ">-</span>';
                    }
                })
                ->addColumn("privilege", fn ($row) => '<span class="badge badge-pill badge-soft-warning font-size-13 p-2">' . $row->ticket->category->name ?? "---" . '</span>')
                ->editColumn("price", fn ($row) => formatPrice($row->price))
                ->editColumn("created_at", fn ($row) => formatDate($row->created_at))
                ->rawColumns(['action', 'store_id', 'is_used', 'privilege', 'is_expired', 'gate_no', 'payment_status'])
                ->make(true);
        }

        $matches = Game::whereHas("tickets")->get()->map(function ($match) {
            return [
                "id" => $match->id,
                "name" => $match->home->name . " " . __('translation.vs') . " " . $match->away->name . " | " . formatDateWithTimezone($match->match_time),
            ];
        });

        $stores = Store::pluck("name", "id");
        // remove website from array, for now
        $stores->forget("3");

        $categories = Ticket::query()->get()->pluck("category.name", "category_id");
        $gates = Gate::query()->get()->pluck("gate_no");

        return view('admin.orders.index', compact('matches', 'stores', 'categories', 'gates'));
    }

    public function show(Order $order)
    {
        //check permission
        $this->authorize("order_view");

        $order->load("match", "ticket:id,seat_id,category_id", "ticket.category:id,name", "store:id,name");
        return view('admin.orders.show', compact("order"));
    }
}

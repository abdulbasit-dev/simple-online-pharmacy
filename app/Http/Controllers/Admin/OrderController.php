<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        //check permission
        $this->authorize("order_view");

        if ($request->ajax()) {
            $data = Order::query()
                ->with("medicine:id,name", "customer:id,name")
                ->when($request->status, function ($query) use ($request) {
                    switch ($request->status) {
                        case 'pending':
                            return $query->where('status', OrderStatus::PENDING->value);
                            break;

                        case 'accepted':
                            return $query->where('status', OrderStatus::ACCEPTED->value);
                            break;
                        case 'canceled':
                            return $query->where('status', OrderStatus::CANCELED->value);
                            break;
                        default:
                            return $query;
                            break;
                    }
                })
                ->when($request->matchId, function ($query) use ($request) {
                    return $query->where('match_id', $request->matchId);
                })
                ->when($request->serialNumber, function ($query) use ($request) {
                    return $query->where('serial_number', "like", "%" . $request->serialNumber . "%");
                })
                ->when($request->transactionId, function ($query) use ($request) {
                    return $query->where('transaction_id', "like", "%" . $request->transactionId . "%");
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
                    if (auth()->user()->can("order_edit"))
                        $td .= '<button type="button" class="btn btn-sm btn-info waves-effect waves-light me-1 cancel-btn" data-bs-toggle="modal" data-bs-target="#changeStatusModal" data-url="' . route("admin.orders.changeStatus", $row->id) . '">Change Status</button>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->editColumn("status", fn ($row) => $row->status->getLabelHtml())
                ->editColumn("total", fn ($row) => formatPrice($row->total))
                ->editColumn("created_at", fn ($row) => formatDateWithTimezone($row->created_at))
                ->rawColumns(['action', 'store_id', 'is_used', 'privilege', 'is_expired', 'gate_no', 'status'])
                ->make(true);
        }

        return view('admin.orders.index');
    }

    public function show(Order $order)
    {
        //check permission
        $this->authorize("order_view");

        $order->load("medicine", "customer");
        return view('admin.orders.show', compact("order"));
    }

    public function changeStatus(Request $request, Order $order)    
    {
        try {
            $status = 0;
            if ($request->status == 'accept') {
                $status = OrderStatus::ACCEPTED;
            } else if ($request->status == 'cancel') {
                $status = OrderStatus::CANCELED;
                $order->medicine->update([
                    "quantity" => $order->medicine->quantity + $order->quantity,
                ]);
            }

            $order->update([
                'status' => $status
            ]);

            return $this->jsonResponse(true, "Order status updated successfully.", Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json(['message' => showErrorMessage($th)], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

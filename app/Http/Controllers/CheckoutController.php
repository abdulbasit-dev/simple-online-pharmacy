<?php

namespace App\Http\Controllers;

use App\Enums\TicketType;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Section;
use App\Models\Ticket;
use App\Services\SectionQuantityService;
use App\Services\SendTicketEmailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PDF;
use URL;

use function PHPUnit\Framework\throwException;

class CheckoutController extends Controller
{
    protected $ticketEmailService;
    protected $sectionQuantityService;

    public function __construct(SendTicketEmailService $ticketEmailService, SectionQuantityService $sectionQuantityService)
    {
        $this->ticketEmailService = $ticketEmailService;
        $this->sectionQuantityService = $sectionQuantityService;
    }

    public function checkout(Request $request)
    {
        $ticketSession = $request->session()->get('ticketSession');

        // if session is empty redirect to season ticket page
        if (!isset($ticketSession)) {
            return redirect()->route('ticketSession');
        }

        $remainAmount = $this->sectionQuantityService->getRemainQuantity($ticketSession["section_id"], $ticketSession["qty"]);

        // check quantity
        if ($remainAmount < $ticketSession["qty"]) {
            // forget session
            $request->session()->forget('ticketSession');

            return redirect()->back()->with([
                "message" => __("app.messages.only_amount_ticket_left", ["amount" => $remainAmount]),
                "icon" => "info",
            ]);
        }

        $accounts = Account::query()
            ->with('paymentMethod:id,name')
            ->whereHas("paymentMethod", function ($query) {
                $query->active();
            })
            ->get();

        return view('frontend.pages.checkout', compact('ticketSession', 'accounts'));
    }

    public function createOrder(Request $request)
    {
        // start transaction
        DB::beginTransaction();
        try {

            //validation
            $validator = Validator::make($request->all(), [
                "paymentMethodId" => ['required', 'integer', 'exists:payment_methods,id'],
                "paymentMethodName" => ['required', 'string', 'exists:payment_methods,name'],
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'result' => false,
                        'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                        'message' => __('app.messages.invalid_payment_method'),
                        "data" => null,
                        "errors" => $validator->errors()->all()
                    ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $ticketSession = $request->session()->get('ticketSession');
            $ticketType  = $ticketSession["ticket_type"];
            $paymentMethodId = $request->get('paymentMethodId');
            $paymentMethodName = $request->get('paymentMethodName');

            Log::info("Payment Method Id: " . $paymentMethodId);
            Log::info("Payment Method Name: " . $paymentMethodName);

            $qty = $ticketSession["qty"];

            // check if ticket exists
            $ticket = Ticket::query()
                ->where("ticket_type", $ticketType)
                ->where("category_id", $ticketSession["category_id"])
                ->where("age_group_id", $ticketSession["age_group_id"])
                ->first();

            // find section
            $section = Section::query()
                ->where("id", $ticketSession["section_id"])
                ->first();

            if (!$ticket) {
                return response()->json(
                    [
                        'result'  => false,
                        'status'  => Response::HTTP_NOT_FOUND,
                        'message' => "Ticket not found",
                        "data"    => null,
                        "errors"  => null
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }

            // check if ticket is not expired
            if ($ticket->expire_at < now()) {
                return response()->json(
                    [
                        'result' => false,
                        'status' => Response::HTTP_BAD_REQUEST,
                        'message' => "This Ticket is Expired, You Can't Order",
                        "data" => null,
                        "errors" => null
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // check if ticket is not available
            if ($section->remain_qty == 0 || $section->remain_qty < $qty) {
                return response()->json(
                    [
                        'result' => false,
                        'status' => Response::HTTP_BAD_REQUEST,
                        'message' => "Ticket Sold Out",
                        "data" => null,
                        "errors" => null
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // create customer
            $customer = Customer::firstOrCreate(
                [
                    "email" => $ticketSession["email"],
                ],
                [
                    "phone" => $ticketSession["phone"],
                    "name" => $ticketSession["name"],
                ]
            );

            $orderId = generateRandomString();

            // create order according to qty
            for ($i = 1; $i <= $qty; $i++) {
                // GENERATE SERIAL NUMBER
                // today day and month in 2 digits
                $day = date('d');
                $second = date('s');
                $month = date('m');
                $year = date('Y');

                // generate TicketNumber
                $ticketNumber = strtotime(date('Y-m-d H:i:s'))  . rand(1000000000, 9999999999);

                $sequence = 0;
                $serialNumberPrefix = "";
                // different path for season ticket and match ticket
                if (TicketType::getEnumText($ticketType) == "SEASON_TICKET") {
                    $formatedPath = "season_tickets/" . strtolower($ticket->category->name) . "_" . $ticketNumber . "_" . $year;
                    $serialNumberPrefix = "ST";
                    $sequence = $section->sold_season_ticket_qty;
                } else {
                    $formatedPath = "match_tickets/" . strtolower($ticket->category->name) . "_" . $ticketNumber . "_" . $year;
                    $serialNumberPrefix = "MT";
                    $sequence = $section->sold_match_ticket_qty;
                }

                // 5 digit from ticket sold_qty
                $sequenceNo = str_pad($sequence + $i, 5, '0', STR_PAD_LEFT);

                // generate serial number
                $serialNumber = $serialNumberPrefix . $year . "-" . $day . $month . $second . "T" . $sequenceNo;

                $qrCode = $serialNumber . "_" . $ticketNumber;

                // generate qr code and get file path
                if (config("app.media_disk") == 's3') {
                    if (config('app.env') == 'production') {
                        $fileNamePath = 'qrcodes/' . $formatedPath . '.png';
                    } elseif (config('app.env') == 'dev') {
                        $fileNamePath = 'dev_qrcodes/' . $formatedPath . '.png';
                    } else {
                        $fileNamePath = 'local_qrcodes/' . $formatedPath . '.svg';
                    }
                } else {
                    $fileNamePath = 'qrcodes/' . $formatedPath . '.png';
                }

                //GENERATE QRCODE
                // generate qr code and upload to s3 bucket
                $qrImage = QrCode::margin(1);

                // if the app in production mode, then change the type of qr code to png
                if (config('app.env') == 'production') {
                    $qrImage->format('png');
                }

                $qrImage = $qrImage->style("round")
                    ->backgroundColor(255, 255, 255)
                    ->style('round')
                    ->size(200)
                    ->generate($qrCode);

                //upload to s3
                Storage::disk('s3')->put($fileNamePath, $qrImage);

                Order::create([
                    "match_id"               => $ticketSession["match_id"] ?? null,
                    "ticket_id"              => $ticket->id,
                    "order_id"               => $orderId,
                    "customer_id"            => $customer->id,
                    "order_id"               => $orderId,
                    "section_id"             => $ticketSession["section_id"],
                    "payment_method_id"      => $paymentMethodId,
                    "serial_number"          => $serialNumber,
                    "ticket_number"          => $ticketNumber,
                    "ticket_type"            => $ticket->ticket_type,
                    "gate_no"                => null,
                    "transaction_id"         => null,
                    "price"                  => $ticketSession["price"],
                    "qr_image"               => config("app.aws_url") . $fileNamePath,
                    "qr_code"                => $qrCode,
                    "expire_at"              => $ticket->expire_at,
                ]);
            }

            // update stock section
            $this->sectionQuantityService->decrementSectionQuantity($ticketSession["section_id"], $qty, TicketType::getEnumText($ticketType));

            switch ($paymentMethodName) {
                case "swish": // swish
                    $route = route("payment.swish.paymentRequest", ["order_id" => $orderId]);
                    break;
                case "stripe": // fastpay
                    $route = route('payment.stripe.createSession', ["order_id" => $orderId]);
                    break;
                default:
                    throw new \Exception("Payment Method Not Found");
                    break;
            }

            // commit transaction
            DB::commit();

            Log::info("Payment ROUTE: " . $route);
            return redirect($route);
        } catch (\Throwable $th) {
            // rollback transaction
            DB::rollback();
            throw $th;
        }
    }

    public function success(Request $request)
    {
        if ($request->has("order_id")) {

            $orderQuery = Order::query()->where("order_id",  $request->order_id);
            $queryUpdateEmailStatus = clone $orderQuery;
            $orders = $orderQuery->get();

            // check if orders is empty
            if ($orders->isEmpty() && $request->has("order_id")) {
                abort(404);
            }

            // check if order status is paid
            if ($orders->first()->payment_status->isUnpaid()) {
                abort(404);
            }

            // send email to customer
            $this->ticketEmailService->sendMail($orders, $queryUpdateEmailStatus);

            return view('frontend.payment.success', compact('orders'));
        }

        return view('frontend.payment.success');
    }

    public function ticketDownload($orderId)
    {
        try {
            $order = Order::query()
                ->with('section', 'ticket.category', "match")
                ->where('id', $orderId)
                ->first();

            if (!$order) {
                return redirect()->back()->with([
                    "message" => __("app.order_not_found"),
                    "icon" => "error",
                ]);
            }

            if ($order->is_ticket_downloaded) {
                return redirect()->back()->with([
                    "message" => __("app.ticket_already_downloaded"),
                    "icon" => "error",
                ]);
            }

            // Check if order is paid
            if ($order->payment_status->isUnpaid()) {
                return redirect()->back()->with([
                    "message" => __("app.messages.ticket_not_paid"),
                    "icon" => "error",
                    "timer" => 5000,
                ]);
            }

            $data = [
                "match"         => $order->match->home->name . " - " . $order->match->away->name,
                "match_time"    => Carbon::parse($order->match->match_time)->format('Y-m-d H:i'),
                "stadium"       => "Studenternas IP",
                "name"          => $order->customer->name,
                "ticket_type"   => $order->ticket_type->name,
                "order_id"      => $order->order_id,
                "ticket_number" => $order->ticket_number,
                "serial_number" => $order->serial_number,
                "section"       => __("app.section") . " " . $order->section->section_no,
                "category"      => $order->ticket->category->name,
                "price"         => formatPrice($order->price),
                "qr_image"      => $order->qr_image,
                "club_logo"     => URL::asset('assets/images/logo.png'),
                "supporter"     => $order->ticket->supporter->name,
                "age_group"     => $order->ticket->ageGroup->name,
                "season_year"   => date('Y'),
            ];

            if ($order->ticket_type->isSeasonTicket()) {
                $pdf = PDF::loadView('frontend.pdf.season-ticket', $data);
            } else {
                $pdf = PDF::loadView('frontend.pdf.match-ticket', $data);
            }

            $order->is_ticket_downloaded = 1;
            $order->save();

            $pdfName = $order->ticket_number . ".pdf";

            return $pdf->download($pdfName);
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()->with([
                "message" => __("app.messages.error_message"),
                "icon" => "error",
            ]);
        }
    }

    public function cancel(Request $request)
    {
        // start transaction
        DB::beginTransaction();
        try {
            $ordersQuery = Order::query()->where("order_id", $request->order_id);

            // check if order_id is not exist or orders is empty
            if (!$request->has("order_id") || $ordersQuery->count() == 0) {
                return view('frontend.payment.cancel');
            }

            $order = $ordersQuery->first();

            // check if orders is empty
            if ($order->payment_status->isPaid()) {
                return view('frontend.payment.cancel');
            }
            $ordersCount =  $ordersQuery->count();

            // force delete orders(not soft delete)
            Order::query()->where("order_id", $request->order_id)->forceDelete();

            // update stock of tickets
            // $this->ticketStockService->incrementTicket($order->section_id, $ordersCount);
            $ticketType = str_replace(" ", "_", strtoupper($order->ticket_type->getLabelText()));
            $this->sectionQuantityService->incrementSectionQuantity($order->section_id, $ordersCount, $ticketType);

            // force delete orders(not soft delete)
            $ordersQuery->forceDelete();

            // commit transaction
            DB::commit();

            return view('frontend.payment.cancel');
        } catch (\Throwable $th) {
            // rollback transaction
            DB::rollback();
            throw $th;
        }
    }
}

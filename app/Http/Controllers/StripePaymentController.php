<?php

namespace App\Http\Controllers;

use App\Enums\OrderEmailStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Mail\SeasonTicketEmail;
use App\Models\Order;
use App\Models\StripePayment;
use App\Services\SendTicketEmailService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripePaymentController extends Controller
{

    protected $ticketEmailService;

    public function __construct(SendTicketEmailService $ticketEmailService)
    {
        $this->ticketEmailService = $ticketEmailService;
    }

    public function createSession(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $orders = Order::where("order_id", $request->order_id)->get();

            if ($orders->count() == 0) {
                return redirect()->route('payment.cancel');
            }

            $order = $orders->first();
            $ticket = $order->ticket;
            $price = $order->price * 100;
            $totalQuantity = $orders->count();

            $stripeSuccessUrl = route('payment.stripe.success', ['order_id' => $order->order_id]);
            $stripeCancelUrl = route('payment.stripe.cancel', ['order_id' => $order->order_id]);

            Log::info("Stripe success url: " . $stripeSuccessUrl);
            Log::info("Stripe cancel url: " . $stripeCancelUrl);

            // path basit99dev@gmail.com email to stripe
            $session = Session::create([
                'line_items'  => [
                    [
                        'price_data' => [
                            'currency'     => systemCurrency(),
                            'product_data' => [
                                'name' => $ticket->name,
                            ],
                            'unit_amount' => $price,
                        ],
                        'quantity' => $totalQuantity,
                    ],
                ],
                'mode'           => 'payment',
                "customer_email" => $order->customer->email,
                'success_url'    => $stripeSuccessUrl,
                'cancel_url'     => $stripeCancelUrl,
            ]);

            // save session info to stripe_payments table
            $stripePayment = StripePayment::create([
                "payment_intent" => $session->payment_intent,
                "payment_status" => 0,
                "amount" => $orders->sum("price"),
                "currency" => systemCurrency(),
            ]);

            foreach ($orders as $order) {
                $order->stripe_payment_id = $stripePayment->id;
                $order->save();
            }

            Log::info("Stripe session url: " . $session->url);
            // return redirect()->away($session->url);
            return response()->json(
                [
                    'result' => false,
                    'status' => Response::HTTP_OK,
                    'message' => "success",
                    "data" => [
                        "stripeUrl" => $session->url,
                        "orderId" => $order->order_id,
                    ],
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function webhook(Request $request)
    {
        Log::info("Stripe Webhook Fun: " . $request->fullUrl());
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        // try {
        //     $event = \Stripe\Webhook::constructEvent(
        //         $payload,
        //         $sig_header,
        //         $endpoint_secret
        //     );
        // } catch (\UnexpectedValueException $e) {
        //     // Invalid payload
        //     Log::error("Stripe Webhook Fun: Invalid payload: " . $e->getMessage());
        //     return response('', 400);
        // } catch (\Stripe\Exception\SignatureVerificationException $e) {
        //     // Invalid signature
        //     Log::error("Stripe Webhook Fun: Invalid signature: " . $e->getMessage());
        //     return response('', 400);
        // }


        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::error("Stripe Webhook Fun: Invalid payload: " . $e->getMessage());
            return response('', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                Log::info("Stripe Webhook Fun: checkout.session.completed: " . $session);

                $stripePayment =  StripePayment::query()->where("payment_intent", $session->payment_intent)->first();
                $stripePayment->payment_status = PaymentStatus::PAID;
                $stripePayment->save();

                $orderQuery = Order::query()->where("stripe_payment_id", $stripePayment->id);
                $orders = $orderQuery->get();
                $queryUpdateEmailStatus = clone $orderQuery;
                $queryUpdatePaymentStatus = clone $orderQuery;

                // send email to customer
                $this->ticketEmailService->sendMail($orders, $queryUpdateEmailStatus);

                // update payment status to paid
                $queryUpdatePaymentStatus->update(["payment_status" => PaymentStatus::PAID]);
                break;


                // ... handle other event types
            default:
                Log::error("Stripe Webhook Fun: Unexpected event type: " . $event->type);
                return response('Stripe Webhook Fun: Unexpected event type', 400);
        }

        return response('');
    }

    public function success(Request $request)
    {
        Log::info("Stripe Success Fun: success url: " . $request->fullUrl());

        $orderQuery = Order::query()->where("order_id", $request->order_id);

        if ($orderQuery->count() == 0) {
            return redirect()->route('payment.cancel');
        }

        $order = $orderQuery->first();

        $stripePayment = StripePayment::find($order->stripe_payment_id);

        $stripePayment->payment_status = PaymentStatus::PAID;
        $stripePayment->save();

        Order::query()->where("order_id", $request->order_id)->update([
            "payment_status" => PaymentStatus::PAID,
        ]);

        return redirect()->route('payment.success', ['order_id' => $order->order_id]);
    }

    public function cancel(Request $request)
    {
        Log::info("Stripe Cancel Fun: cancel url: " . $request->fullUrl());

        $orderQuery = Order::query()->where("order_id", $request->order_id);

        if ($orderQuery->count() == 0) {
            return redirect()->route('payment.cancel');
        }

        $order = $orderQuery->first();

        $stripePayment = StripePayment::find($order->stripe_payment_id);
        $stripePayment->payment_status = PaymentStatus::CANCELLED;
        $stripePayment->save();

        Order::query()->where("order_id", $request->order_id)->update([
            "payment_status" => PaymentStatus::CANCELLED,
        ]);

        return redirect()->route('payment.cancel', ['order_id' => $order->order_id]);
    }
}

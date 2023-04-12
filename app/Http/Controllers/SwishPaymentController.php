<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Log;
use URL;

class SwishPaymentController extends Controller
{
    public function paymentRequest(Request $request, $orderId)
    {
        // REMOVE 🧹
        if (!$orderId)
            $orderId = generateRandomString();

        $orders = Order::where("order_id",$orderId)->get();

        if (!$orders->count()) {
            return $this->jsonResponse(false, "Order not found", Response::HTTP_NOT_FOUND);
        }


        $client = new Client([
            'base_uri' => 'https://mss.cpc.getswish.net/swish-cpcapi/api/v2/',
            'timeout' => 30,
            'verify' => public_path('ssl/Swish_TLS_RootCA.pem'),
            'cert' => public_path('ssl/Swish_Merchant_TestCertificate_1234679304.pem'),
            'ssl_key' => public_path('ssl/Swish_Merchant_TestCertificate_1234679304.key'),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        // $paymentRequestId = '2F9C2F35D92340348F130D702E6C' . rand(1000, 9999);
        // $paymentRequestId = strtoupper(bin2hex(random_bytes(16)));
        $endpoint = 'paymentrequests/' . $orderId;

        try {
            $response = $client->request('PUT', $endpoint, [
                'json' => [
                    'payeePaymentReference' => '0123456789',
                    'callbackUrl' => "https://sportsclubticket.test/payment/swish/callback",
                    //  'callbackUrl' => route("payment.swish.callback"),
                    'payeeAlias' => '1231181189',
                    'amount' => $orders->sum("price"),
                    'currency' => 'SEK',
                    'message' => 'Season Ticket ' . date('Y') . ' - ' . date('Y', strtotime('+1 year')),
                ],
            ]);

            if ($response->getStatusCode() == 201) {
                $location = $response->getHeaderLine('Location');
                $PaymentRequestToken = $response->getHeaderLine('PaymentRequestToken');
                $paymentRequestId = substr($location, strrpos($location, '/') + 1);

                // GET QRCODE API CALLs
                $client = new Client([
                    'base_uri' => 'https://mpc.getswish.net/qrg-swish/api/v1/commerce',
                    'timeout' => 30,
                ]);

                $response = $client->request('POST', '', [
                    'json' => [
                        "format" => "png",
                        "size" => 300,
                        "token" => $PaymentRequestToken
                    ],
                ]);

                if ($response->getStatusCode() == 200) {
                    $img = $response->getBody();

                    // FIRST METHOD
                    // convert to base 64
                    // $img = base64_encode($img);
                    // return response($img)->header('Content-Type', 'image/png');

                    // SECOND METHOD
                    // Store the file
                    $fileName = "swish_qrcodes/" . $paymentRequestId . ".png";
                    $storage = Storage::disk('public')->put($fileName, $img);

                    if (!$storage) {
                        return $this->jsonResponse(false, "Qr Code not generated", Response::HTTP_INTERNAL_SERVER_ERROR);
                    }

                    $data = [
                        "orderId" => $orderId,
                        "qrcode" => URL::asset("uploads/" . $fileName)
                    ];

                    return $this->jsonResponse(true, "Qr Code generated successfully", Response::HTTP_OK, $data);
                }

                // QRCODE V2
                // // http POST request to https://api.swish.nu/qr/v2/prefilled
                // $client = new Client([
                //     'base_uri' => 'https://api.swish.nu/qr/v2/prefilled',
                //     'timeout' => 30,
                // ]);


                // $response = $client->request('POST', '', [
                //     'json' => [
                //         'payee' => '0123456789'
                //     ],
                // ]);


                // Log::info("respoons2 " . $response->getStatusCode());

                // if ($response->getStatusCode() == 200) {
                //     // retrun response
                //     $img = $response->getBody();
                //     return response($img)->header('Content-Type', 'image/png');
                // }
            } else {
                return response()->json([
                    'error' => 'Error',
                    'message' => 'Error',
                    'status' => $response->getStatusCode(),
                ], 500);
            }
        } catch (RequestException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => $e->getResponse()->getBody()->getContents(),
                'status' => $e->getCode(),
                "paymentRequestId" => $paymentRequestId,
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        // NOTE: this is a callback from fib call when user pay or scan the qr code
        Log::info("Swish callback");
        Log::info($request->all());

        try {
            if ($request->status == "PAID") {
                Order::query()->where("order_id",  $request->paymentReference)->update([
                    "payment_status" => 1, // paid
                ]);
            }

            return $this->jsonResponse(true, "status updated", Response::HTTP_ACCEPTED);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function cancel(Request $request)
    {
        try {
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function checkStatus(Request $request)
    {
        try {
            Log::info("FIB check status");
            $order = Order::query()
                ->select(["order_id", "payment_status"])
                ->where("fib_payment_id",  $request->payment_id)
                ->first();

            return $this->jsonResponse(true, "status updated", Response::HTTP_ACCEPTED, $order);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CardSellingApiKey
{
    public function handle(Request $request, Closure $next)
    {
        // take X-Authorization header from request and check if it is valid
        if ($request->header('X-Authorization') != config('app.card_selling_api_key')) {
            return response()->json([
                'result' => false,
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => "Unauthenticated.",
            ], 401);
        }

        // add new value to request body
        // card-selling => 2
        $request->merge(['store_id' => 2]);
        $request->merge(['store_name' => "cardSelling"]);
        return $next($request);
    }
}

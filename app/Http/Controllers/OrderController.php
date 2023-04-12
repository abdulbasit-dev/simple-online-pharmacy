<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'match_id' => 'required|exists:games,id',
            'category_id' => 'required|exists:categories,id',
            'seat_id' => 'required|exists:seats,id',
            'quantity' => 'required|integer|min:1',
            'account_id' => 'required|exists:accounts,id',
        ]);
        // create order
        $order = auth()->user()->orders()->create([
            'match_id' => $request->match_id,
            'category_id' => $request->category_id,
            'seat_id' => $request->seat_id,
            'quantity' => $request->quantity,
            'account_id' => $request->account_id,
        ]);
        // redirect to payment page
        return redirect()->route('makeOrder', ['order' => $order]);
    }
}

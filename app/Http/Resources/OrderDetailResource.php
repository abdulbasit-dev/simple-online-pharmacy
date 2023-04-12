<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class OrderDetailResource extends JsonResource
{

    public function toArray($request)
    {
        $data = $this->resource;
        return [
            'result' => true,
            'status' => Response::HTTP_OK,
            'message' => "Order details",
            'data' => [
                "serial_number"                 => $data->serial_number,
                "ticket_number"                 => $data->ticket_number,
                "payment_method"                => $data->store->name,
                "ticket_privilege"              => $data->ticket->category->name,
                "seat_category"                 => $data->ticket->seat->name ?? "",
                "gate_no"                       => $data->gate_no ?? "",
                "venue"                         => "Duhok Stadium",
                "transaction_id"                => $data->transaction_id,
                "match_time"                    => Carbon::parse($data->match->match_time)->format("D, M d, g:i A"),
                "match_team_names"              => $data->match->home->name . " - " . $data->match->away->name,
                "is_used"                       => $data->is_used,
                "qr_code"                       => $data->qr_code,
                "qr_image"                      => $data->qr_image,
                "amount"                        => $data->price,
                "used_at"                       => $data->used_at,
                "expire_at"                     => $data->expire_at,
                "created_at"                    => $data->created_at,
            ]
        ];
    }
}

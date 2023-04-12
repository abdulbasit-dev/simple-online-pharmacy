<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        "status" => OrderStatus::class,
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}

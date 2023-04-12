<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedByTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Medicine extends Model implements HasMedia
{
    use SoftDeletes,  InteractsWithMedia;

    protected $guarded = [];


    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    // relationships
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function origin()
    {
        return $this->belongsTo(Origin::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

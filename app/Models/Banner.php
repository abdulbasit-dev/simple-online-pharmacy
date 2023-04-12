<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Banner extends Model implements HasMedia
{
    use InteractsWithMedia;
    use CreatedByTrait;

    protected $guarded = [];

    // protected static function booted()
    // {
    //     static::addGlobalScope(new ActiveScope);
    // }

    public function scopeActive($query)
    {
        return $query->where("status", 1);
    }
}

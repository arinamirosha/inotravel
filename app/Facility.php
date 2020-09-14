<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $guarded = [];

    public function house()
    {
        return $this->belongsToMany(House::class);
    }

    public function facilitiesExist()
    {
        return $this->internet || $this->wifi || $this->cable_tv || $this->conditioner || $this->washer;
    }
}

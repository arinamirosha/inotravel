<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restriction extends Model
{
    protected $guarded = [];

    public function house()
    {
        return $this->hasOne(House::class);
    }

    public function restrictionsExist()
    {
        return $this->animals || $this->houseplants || $this->no_smoke || $this->no_drink;
    }
}

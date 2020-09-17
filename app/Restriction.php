<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restriction extends Model
{
    protected $guarded = [];

    public function house()
    {
        return $this->belongsToMany(House::class);
    }

    public function image()
    {
        return "/images/{$this->name }.png";
    }
}

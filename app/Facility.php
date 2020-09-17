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

    public function image()
    {
        return "/images/{$this->name }.png";
    }
}

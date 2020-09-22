<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [];

    /**
     * Many facilities to many houses
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function house()
    {
        return $this->belongsToMany(House::class);
    }

    /**
     * Get facility's image
     *
     * @return string
     */
    public function image()
    {
        return "/images/{$this->name }.png";
    }
}

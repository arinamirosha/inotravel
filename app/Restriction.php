<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restriction extends Model
{
    protected $fillable = [];

    /**
     * Many restrictions to many houses
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function house()
    {
        return $this->belongsToMany(House::class);
    }

    /**
     * Get restriction's image
     *
     * @return string
     */
    public function image()
    {
        return "/images/{$this->name }.png";
    }
}

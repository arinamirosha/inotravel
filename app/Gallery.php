<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    protected $fillable = [
        'image',
    ];

    /**
     * One image to one house
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function house()
    {
        return $this->belongsTo(House::class);
    }

    /**
     * Get path to gallery's image
     *
     * @return string
     */
    public function galleryImage()
    {
        return Storage::url($this->image);
    }
}

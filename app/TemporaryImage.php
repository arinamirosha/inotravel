<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TemporaryImage extends Model
{
    protected $fillable = ['image'];

    /**
     * Many temporary images to one user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get path of temporary image
     *
     * @return string
     */
    public function tempImage()
    {
        return Storage::url($this->image);
    }
}

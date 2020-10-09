<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}

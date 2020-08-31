<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class House extends Model
{
    protected $guarded = ['facilities', 'restrictions'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function restriction()
    {
        return $this->belongsTo(Restriction::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function houseImage()
    {
        $imagePath = ($this->image) ? $this->image : 'icons/noImage.svg';
        return '/storage/' . $imagePath;
    }

    public static function boot()
    {
        parent::boot();
        static::deleted(function($house)
        {
            $house->deleteImage();
            $house->facility()->delete();
            $house->restriction()->delete();
        });
    }

    public function deleteImage()
    {
        if ($this->image){
            Storage::delete("public/$this->image");
            $this->image=null;
        }
    }
}

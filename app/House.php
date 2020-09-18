<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class House extends Model
{
    protected $fillable = [
        'name', 'city', 'address', 'places', 'info', 'image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'houses_facilities', 'house_id', 'facility_id');
    }

    public function restrictions()
    {
        return $this->belongsToMany(Restriction::class, 'houses_restrictions', 'house_id', 'restriction_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function houseImage()
    {
        $imagePath = ($this->image) ? Storage::url($this->image) : '/images/noImage.svg';
        return $imagePath;
    }

    public static function boot()
    {
        parent::boot();
        static::deleted(function($house)
        {
            $house->deleteImage();

            $booksToMail = $house->bookings()
                ->where('status', Booking::STATUS_BOOKING_ACCEPT)
                ->where('arrival', '>=', Carbon::now())
                ->get();

            $house->bookings()->delete();
            $house->facilities()->detach();
            $house->restrictions()->detach();
        });
    }

    public function deleteImage()
    {
        if ($this->image){
            Storage::delete("public/$this->image");
            $this->image=null;
        }
    }

    public function isFree($arrival, $departure)
    {
        $isFree = ! $this->bookings()
        ->where('status', '=', Booking::STATUS_BOOKING_ACCEPT)
        ->where(function ($query) use ($arrival, $departure) {
            $query
                ->whereBetween('departure', [$arrival, $departure])
                ->orWhereBetween('arrival', [$arrival, $departure]);
        })
        ->exists();

        return $isFree;
    }
}

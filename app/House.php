<?php

namespace App;

use App\Jobs\SendBookingDeletedEmail;
use App\Mail\BookingDeletedNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class House extends Model
{
    protected $fillable = [
        'name',
        'city',
        'address',
        'places',
        'info',
        'image',
    ];

    /**
     * Many houses to one user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Many houses has many facilities
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'houses_facilities', 'house_id', 'facility_id');
    }

    /**
     * Many houses has many restrictions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function restrictions()
    {
        return $this->belongsToMany(Restriction::class, 'houses_restrictions', 'house_id', 'restriction_id');
    }

    /**
     * One house to many bookings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get path to house's image
     *
     * @return string
     */
    public function houseImage()
    {
        $imagePath = ($this->image) ? Storage::url($this->image) : '/images/noImage.svg';

        return $imagePath;
    }

    /**
     * Delete house and create job to send emails
     */
    public static function boot()
    {
        parent::boot();
        static::deleted(function ($house) {
            $booksToMail =
                $house->bookings()
                    ->where('status', Booking::STATUS_BOOKING_ACCEPT)
                    ->where('arrival', '>=', Carbon::now()->format('Y-m-d'))
                    ->addSelect(['email' => User::select('email')->whereColumn('user_id', 'users.id')])
                    ->get();

            SendBookingDeletedEmail::dispatch($booksToMail, $house->name, $house->city)->delay(now()->addSeconds(10));

            $house->bookings()->delete();
            $house->deleteImage();
            $house->facilities()->detach();
            $house->restrictions()->detach();
        });
    }

    /**
     * Delete house's image
     */
    public function deleteImage()
    {
        if ($this->image) {
            Storage::delete("public/$this->image");
            $this->image = null;
        }
    }

    /**
     * Check if house free for booking
     *
     * @param $arrival
     * @param $departure
     * @return bool
     */
    public function isFree($arrival, $departure, $people)
    {
//        $isFree = ! $this->bookings()
//        ->where('status', '=', Booking::STATUS_BOOKING_ACCEPT)
//        ->where(function ($query) use ($arrival, $departure) {
//            $query
//                ->whereBetween('departure', [$arrival, $departure])
//                ->orWhereBetween('arrival', [$arrival, $departure]);
//        })
//        ->exists();

        $isFree = true;
        for ($i = Carbon::parse($arrival); $i <= Carbon::parse($departure); $i = $i->add(1, 'day')) {

            $date = $i->format('Y-m-d');

            $full = (
                    $this->bookings()
                        ->where('status', '=', Booking::STATUS_BOOKING_ACCEPT)
                        ->where('arrival', '<=', $date)
                        ->where('departure', '>=', $date)
                        ->sum('people')
                    + $people
                ) > $this->places;

            if ($full) {
                $isFree = false;
                break;
            }
        }

        return $isFree;
    }
}

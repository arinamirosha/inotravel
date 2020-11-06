<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'surname', 'email', 'password', 'admin'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * One user to many houses
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function houses()
    {
        return $this->hasMany(House::class)->orderBy('created_at', 'DESC');
    }

    /**
     * One user to many bookings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class)->orderBy('created_at', 'DESC');
    }

    /**
     * One user to many history
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories()
    {
        return $this->hasMany(BookingHistory::class)->orderBy('created_at', 'DESC');
    }

    /**
     * One user to many temporary images
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function temporaryImages()
    {
        return $this->hasMany(TemporaryImage::class)->orderBy('created_at', 'DESC');
    }

    /**
     * Get count of income bookings
     *
     * @return string
     */
    public function newInBooks()
    {
        $newInBooks = Booking::join('houses', 'houses.id', '=', 'house_id')
            ->where('houses.user_id', '=', $this->id)
            ->where('status', '=', Booking::STATUS_BOOKING_SEND)
            ->select('bookings.*')
            ->count();
        if ($newInBooks != 0) {
            return "(+$newInBooks)";
        } else {
            return '';
        }
    }

    /**
     * Get count of new bookings with answer (accepted or rejected)
     *
     * @return string
     */
    public function unreadOutBooks()
    {
        $unreadOutBooks = Booking::where('user_id', '=', $this->id)
            ->where('new', '=', Booking::STATUS_BOOKING_NEW)
            ->count();
        if ($unreadOutBooks != 0) {
            return "(+$unreadOutBooks)";
        } else {
            return '';
        }
    }
}

<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    const NO_ADMIN = 0;
    const ADMIN = 1;
    const SUPER_ADMIN = 2;
    const ALL = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'surname', 'email', 'password', 'admin', 'avatar'];

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
        return $this->hasMany(House::class);
    }

    /**
     * One user to many bookings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * One user to many histories
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories()
    {
        return $this->hasMany(BookingHistory::class);
    }

    /**
     * One user to many temporary images
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function temporaryImages()
    {
        return $this->hasMany(TemporaryImage::class);
    }

    /**
     * Get path to user's avatar
     *
     * @return string
     */
    public function avatarImg()
    {
        $imagePath = ($this->avatar) ? Storage::url($this->avatar) : '/images/noImage.svg';

        return $imagePath;
    }

    /**
     * Delete user's avatar
     */
    public function deleteAvatar()
    {
        if ($this->avatar)
        {
            Storage::disk('public')->delete($this->avatar);
            $this->avatar = null;
            $this->save();
        }
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
                             ->where('new', '=', Booking::STATUS_BOOKING_NEW)
                             ->where('status', '<>', Booking::STATUS_BOOKING_ACCEPT)
                             ->where('status', '<>', Booking::STATUS_BOOKING_REJECT)
                             ->select('bookings.*')
                             ->count();

        return $newInBooks != 0 ? "(+$newInBooks)" : '';
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
                                 ->where('status', '<>', Booking::STATUS_BOOKING_SEND)
                                 ->where('status', '<>', Booking::STATUS_BOOKING_CANCEL)
                                 ->count();

        return $unreadOutBooks != 0 ? "(+$unreadOutBooks)" : '';
    }

    /**
     * Get count of unread notifications
     *
     * @return string
     */
    public function newNotifications()
    {
        $newNotifications = $this->unreadNotifications->count();

        return $newNotifications != 0 ? "(+$newNotifications)" : '';
    }
}

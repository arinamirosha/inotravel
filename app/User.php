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
    protected $fillable = [
        'name', 'surname', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function houses()
    {
        return $this->hasMany(House::class)->orderBy('created_at', 'DESC');
    }

    public function housesExist()
    {
        return House::where('user_id', '=', $this->id)->get();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class)->orderBy('created_at', 'DESC');
    }

    public function newInBooks()
    {
        $newInBooks = Booking::join('houses', 'houses.id', '=', 'house_id')
            ->where('houses.user_id', '=', $this->id)
            ->whereNull('status')
            ->select('bookings.*')
            ->get()->count();
        if ($newInBooks != 0 ) return "(+$newInBooks)";
        else return '';
    }

    public function unreadOutBooks()
    {
        $unreadOutBooks = Booking::where('user_id', '=', $this->id)
            ->whereNotNull('status')
            ->where('new', true)
            ->get()->count();
        if ($unreadOutBooks != 0 ) return "(+$unreadOutBooks)";
        else return '';
    }
}

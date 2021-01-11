<?php

namespace App;

use App\Events\HouseDeletedEvent;
use App\Jobs\SendBookingDeletedEmail;
use App\Libraries\House\Facades\HouseManager;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class House extends Model
{
    use SoftDeletes;

    const ALL_HOUSES = 'all houses';
    const ONE_HOUSE = 'one house';

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
            event(new HouseDeletedEvent($house));
        });
    }

    /**
     * Delete house's image
     */
    public function deleteImage()
    {
        if ($this->image)
        {
            Storage::delete("public/$this->image");
            $this->image = null;
            $this->save();
        }
    }

    /**
     * Check if house free for booking
     *
     * @param $arrival
     * @param $departure
     * @param $people
     *
     * @return bool
     */
    public function isFree($arrival, $departure, $people)
    {
        $houseId      = $this->id;
        $housesResult = HouseManager::getSqlFreeHouse($arrival, $departure, $people, $houseId, House::ONE_HOUSE);

        return DB::table(DB::raw("($housesResult) x"))->exists();
    }
}

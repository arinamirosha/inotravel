<?php

namespace App;

use App\Jobs\SendBookingDeletedEmail;
use App\Mail\BookingDeletedNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
     * @param $people
     * @return bool
     */
    public function isFree($arrival, $departure, $people)
    {
        $houseId = $this->id;
        $countDays = Carbon::parse($departure)->diffInDays(Carbon::parse($arrival)) + 1;

        $genDate = DB::table(DB::raw("(SELECT ADDDATE('" . $arrival . "', t1 * 10 + t0) gen_date FROM
                (SELECT 0 t0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
                (SELECT 0 t1 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) t1) AS v"))
            ->whereBetween('gen_date', [$arrival, $departure])
            ->toFullSql();

        $result = DB::table(DB::raw("($genDate) AS t"))
            ->select(DB::raw('SUM(people) AS summa'), 't.gen_date', 'houses.id', 'houses.places')
            ->leftJoin('houses', function ($query) use ($houseId) {
                $query->on(DB::raw(1), '=', DB::raw(1))
                    ->where('id', '=', $houseId);
            })
            ->leftJoin('bookings', function ($query) {
                $query->on('houses.id', '=', 'bookings.house_id')
                    ->whereColumn('arrival', '<=', "t.gen_date")
                    ->whereColumn('departure', '>=', "t.gen_date")
                    ->where('bookings.status', '=', Booking::STATUS_BOOKING_ACCEPT);
            })
            ->groupBy('houses.id', 't.gen_date', 'houses.places')
            ->havingRaw("summa <= `houses`.`places` - $people")
            ->orHavingRaw("(summa IS NULL AND houses.places >= $people)")
            ->orderByRaw('id, summa DESC')
            ->toFullSql();

        $finish = DB::table(DB::raw("($result) AS result"))
            ->selectRaw('result.id AS house_id, count(result.id) as count_days')
            ->groupBy('result.id')
            ->toFullSql();

        $isFree = DB::table(DB::raw("($finish) AS finish"))
            ->where('count_days', '=', $countDays)
            ->exists();

        return $isFree;
    }
}

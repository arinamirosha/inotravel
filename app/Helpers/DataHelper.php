<?php

use App\Booking;
use App\House;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Store image and return it's path
 *
 * @param $file
 * @return mixed
 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
 */
function storeImage($file)
{
    $path = Storage::disk('public')->putFile('uploads', $file);
    $image = Image::make(Storage::disk('public')->get($path))->resize(300, 300)->encode();
    Storage::disk('public')->put($path, $image);

    return $path;
}

/**
 * Replace existing image
 *
 * @param $file
 * @param $path
 */
function updateImage($file, $path)
{
    $image = Image::make($file->getPathname())->resize(300, 300)->encode();
    Storage::disk('public')->put($path, $image);
}

/**
 * Get houses that have at least one day that doesn't suit search parameters
 *
 * @param $arrival
 * @param $departure
 * @param $people
 * @return mixed
 */
function getFullHouses($arrival, $departure, $people)
{
    $exceptHouses = DB::table(DB::raw('(select * from
		 (select adddate(\''.$arrival.'\',t1*10 + t0) gen_date from
		 (select 0 t0 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
		 (select 0 t1 union select 1 union select 2 union select 3 union select 4 union select 5) t1) v
		 where gen_date between \''.$arrival.'\' and \''.$departure.'\') as t'))
        ->select([
            DB::raw('SUM(people) AS summa'),
            't.gen_date',
            'bookings.house_id',
        ])
        ->leftJoin('bookings', function ($query) {
            $query->on(DB::raw('1'), '=', DB::raw('1'));
        })
        ->addSelect(['places' => House::select('places')->whereColumn('house_id', 'houses.id')])
        ->whereColumn('arrival', '<=', 't.gen_date')
        ->whereColumn('departure', '>=', 't.gen_date')
        ->where('status', Booking::STATUS_BOOKING_ACCEPT)
        ->groupBy(DB::raw('bookings.house_id, t.gen_date'))
        ->having(DB::raw('places-summa'), '<', $people)
        ->toFullSql();

    $exceptHouses = DB::table((DB::raw("($exceptHouses) AS result")))
        ->select('house_id')->distinct()->toFullSql();

    $exceptHouses = House::rightJoin((DB::raw("($exceptHouses) AS ex")), 'houses.id', '=', 'ex.house_id')
        ->get();

    return $exceptHouses;
}

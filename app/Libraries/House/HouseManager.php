<?php

namespace App\Libraries\House;

use App\Booking;
use App\House;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HouseManager
{
    /**
     * Attach facilities and restrictions to the house
     *
     * @param $reqFac
     * @param $reqRest
     * @param $house
     */
    public function attachToHouse($reqFac, $reqRest, $house)
    {
        $house->facilities()->sync($reqFac);
        $house->restrictions()->sync($reqRest);
    }

    /**
     * Get sql query for getting free houses or check only one
     *
     * @param $arrival
     * @param $departure
     * @param $people
     * @param $whereOrId
     * @param $whatToGet
     * @return mixed
     */
    public function getSqlFreeHouse($arrival, $departure, $people, $whereOrId, $whatToGet)
    {
        $countDays = Carbon::parse($departure)->diffInDays(Carbon::parse($arrival)) + 1;

        // generate 60 days from arrival and get set of dates between arrival and departure
        // each gen_date = each search day
        $genDate = DB::table(DB::raw("(SELECT ADDDATE('" . $arrival . "', t1 * 10 + t0) gen_date FROM
                (SELECT 0 t0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
                (SELECT 0 t1 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) t1) AS v"))
            ->whereBetween('gen_date', [$arrival, $departure])
            ->toFullSql();

        // check every day if it's free
        $result = DB::table(DB::raw("($genDate) AS t"))

            // sum of people for each gen_date - count of busy places or null (free of bookings)
            ->select(DB::raw('SUM(people) AS summa'), 't.gen_date', 'houses.id', 'houses.places')

            // each house with right city or specific house
            ->leftJoin('houses', function ($query) use ($whereOrId, $whatToGet) {

                switch ($whatToGet)
                {
                    case House::ALL_HOUSES: // when searching among all houses
                        $query->on(DB::raw(1), '=', DB::raw(1))
                            ->where('city', 'like', "%$whereOrId%");
                        break;
                    case House::ONE_HOUSE: // when checking if specific house is free
                        $query->on(DB::raw(1), '=', DB::raw(1))
                            ->where('id', '=', $whereOrId);
                        break;
                }

            })

            // each accepted booking with cross of current gen_date or null
            ->leftJoin('bookings', function ($query) {
                $query->on('houses.id', '=', 'bookings.house_id')
                    ->whereColumn('arrival', '<=', "t.gen_date")
                    ->whereColumn('departure', '>=', "t.gen_date")
                    ->where('bookings.status', '=', Booking::STATUS_BOOKING_ACCEPT);
            })

            ->groupBy('houses.id', 't.gen_date', 'houses.places')
            ->havingRaw("summa <= `houses`.`places` - $people") // enough places in house even if there were previously accepted bookings
            ->orHavingRaw("(summa IS NULL AND houses.places >= $people)") // enough places in house
            ->orderByRaw('id, summa DESC')
            ->toFullSql();

        // get column with count of free days
        $finish = DB::table(DB::raw("($result) AS result"))
            ->selectRaw('result.id AS house_id, count(result.id) as count_days')
            ->groupBy('result.id')
            ->toFullSql();

        // count of free days must the same as count of searching days (departure - arrival + 1)
        return DB::table(DB::raw("($finish) AS finish"))
            ->where('count_days', '=', $countDays)
            ->toFullSql();
    }
}

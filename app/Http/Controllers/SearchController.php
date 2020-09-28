<?php

namespace App\Http\Controllers;

use App\Booking;
use App\House;
use App\Http\Requests\SearchRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Comparator\Book;

class SearchController extends Controller
{
    /**
     * Search for right houses, show page with results
     *
     * @param SearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(SearchRequest $request)
    {
        $requestData = $request->all();
        $arrival = $requestData['arrival'];
        $departure = $requestData['departure'];
        $where = $requestData['where'];
        $people = $requestData['people'];

        $countDays = Carbon::parse($departure)->diffInDays(Carbon::parse($arrival)) + 1;

        $genDate = DB::table(DB::raw("(SELECT ADDDATE('" . $arrival . "', t1 * 10 + t0) gen_date FROM
                (SELECT 0 t0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
                (SELECT 0 t1 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) t1) AS v"))
            ->whereBetween('gen_date', [$arrival, $departure])
            ->toFullSql();

        $result = DB::table(DB::raw("($genDate) AS t"))
            ->select(DB::raw('SUM(people) AS summa'), 't.gen_date', 'houses.id', 'houses.places')
            ->leftJoin('houses', function ($query) use ($where) {
                $query->on(DB::raw(1), '=', DB::raw(1))
                    ->where('city', 'like', "%$where%");
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

        $housesResult = DB::table(DB::raw("($finish) AS finish"))
            ->where('count_days', '=', $countDays)
            ->toFullSql();

        $houses = House::rightJoin((DB::raw("($housesResult) AS h")), 'houses.id', '=', 'h.house_id')->get();

        Cookie::queue('arrival', $arrival, 60);
        Cookie::queue('departure', $departure, 60);
        Cookie::queue('people', $people, 60);

        return response()->view('search', [
            'houses' => $houses,
            'searchData' => $requestData,
        ]);
    }
}

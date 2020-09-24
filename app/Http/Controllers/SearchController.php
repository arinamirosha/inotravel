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

//        $exceptHouses = DB::table(DB::raw('(select * from
//		 (select adddate(\''.$arrival.'\',t1*10 + t0) gen_date from
//		 (select 0 t0 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
//		 (select 0 t1 union select 1 union select 2 union select 3 union select 4 union select 5) t1) v
//		 where gen_date between \''.$arrival.'\' and \''.$departure.'\') as t'))
//            ->select([
//                DB::raw('SUM(people) AS summa'),
//                't.gen_date',
//                'bookings.house_id',
//            ])
//            ->leftJoin('bookings', function ($query) {
//                $query->on(DB::raw('1'), '=', DB::raw('1'));
//            })
//            ->addSelect(['places' => House::select('places')->whereColumn('house_id', 'houses.id')])
//            ->whereColumn('arrival', '<=', 't.gen_date')
//            ->whereColumn('departure', '>=', 't.gen_date')
//            ->where('status', Booking::STATUS_BOOKING_ACCEPT)
//            ->groupBy(DB::raw('bookings.house_id, t.gen_date'))
//            ->having(DB::raw('places-summa'), '<', $people)
//            ->toFullSql();
//
//        $exceptHouses = DB::table((DB::raw("($exceptHouses) AS result")))
//            ->select('house_id')->distinct()->toFullSql();
//
//        $exceptHouses = House::rightJoin((DB::raw("($exceptHouses) AS ex")), 'houses.id', '=', 'ex.house_id')
//            ->get();

        $exceptHouses = getFullHouses($arrival, $departure, $people);

        $houses = House::where('city', 'like', "%{$where}%")
            ->where('places', '>=', $people)
            ->get()
            ->diff($exceptHouses);

        Cookie::queue('arrival', $arrival, 60);
        Cookie::queue('departure', $departure, 60);
        Cookie::queue('people', $people, 60);

        return response()->view('search', [
            'houses' => $houses,
            'searchData' => $requestData,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Booking;
use App\House;
use App\Http\Requests\SearchRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
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

        $houses = House::addSelect(['user_name' => User::select('name')->whereColumn('user_id', 'users.id')])
            ->where('city', 'like', "%{$where}%")
            ->where('places', '>=', $people)
//            ->whereDoesntHave('bookings', function ($query) use ($arrival, $departure) {
//                $query
//                    // также в house->isFree()
//                    ->where('status', '=', Booking::STATUS_BOOKING_ACCEPT)
//                    ->where(function ($query) use ($arrival, $departure) {
//                        $query
//                            ->whereBetween('departure', [$arrival, $departure])
//                            ->orWhereBetween('arrival', [$arrival, $departure]);
//                    });
//            })
            ->orderBy('name')
            ->orderBy('user_name')
            ->with('user')
            ->get();

        // не придумала, как вместить в один предыдущий запрос, поэтому так
        $arr = [];
        foreach ($houses as $house) {
            if ( ! $house->isFree($arrival, $departure, $people)) {
                array_push($arr, $house->id);
            }
        }
        $houses = $houses->whereNotIn('id', $arr);

        Cookie::queue('arrival', $arrival, 60);
        Cookie::queue('departure', $departure, 60);
        Cookie::queue('people', $people, 60);

        return response()->view('search', [
            'houses' => $houses,
            'searchData' => $requestData,
        ]);
    }
}

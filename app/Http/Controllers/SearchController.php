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

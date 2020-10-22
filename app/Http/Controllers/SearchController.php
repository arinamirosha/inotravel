<?php

namespace App\Http\Controllers;

use App\Booking;
use App\House;
use App\Http\Requests\SearchRequest;
use App\Libraries\House\Facades\HouseManager;
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
    public function __invoke(SearchRequest $request)
    {
        $requestData = $request->all();
        $arrival = $requestData['arrival'];
        $departure = $requestData['departure'];
        $where = $requestData['where'];
        $people = $requestData['people'];

        $housesResult = HouseManager::getSqlFreeHouse($arrival, $departure, $people, $where, House::ALL_HOUSES);
        $houses = House::rightJoin((DB::raw("($housesResult) AS h")), 'houses.id', '=', 'h.house_id')
            ->addSelect(['user_name' => User::select('name')->whereColumn('user_id', 'users.id')])
            ->orderBy('name')
            ->orderBy('user_name')
            ->get();

        Cookie::queue('arrival', $arrival, 60);
        Cookie::queue('departure', $departure, 60);
        Cookie::queue('people', $people, 60);

        return response()->view('search', [
            'houses' => $houses,
            'searchData' => $requestData,
            'isSearch' => true,
        ]);
    }
}

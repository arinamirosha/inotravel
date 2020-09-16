<?php

namespace App\Http\Controllers;

use App\Booking;
use App\House;
use App\Http\Requests\SearchRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use SebastianBergmann\Comparator\Book;

class SearchController extends Controller
{
    public function index(SearchRequest $request)
    {
        $requestData = $request->all();

        $houses = House::addSelect(['user_name' => User::select('name')->whereColumn('user_id', 'users.id')])
            ->where('city', 'like', "%{$requestData['where']}%")
            ->where('places', '>=', $requestData['people'])
            ->whereDoesntHave('bookings', function ($query) use ($requestData) {
                $query
                    // также в house->isFree()
                    ->where('status', '=', Booking::STATUS_BOOKING_ACCEPT)
                    ->where(function ($query) use ($requestData) {
                        $query
                            ->whereBetween('departure', [$requestData['arrival'], $requestData['departure']])
                            ->orWhereBetween('arrival', [$requestData['arrival'], $requestData['departure']]);
                    });
            })
            ->orderBy('name')
            ->orderBy('user_name')
            ->with('user')
            ->get();

        Cookie::queue('arrival', $requestData['arrival'], 60);
        Cookie::queue('departure', $requestData['departure'], 60);
        Cookie::queue('people', $requestData['people'], 60);

        return response()->view('search', [
            'houses' => $houses,
            'searchData' => $requestData
        ]);
    }
}

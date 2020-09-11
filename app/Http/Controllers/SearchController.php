<?php

namespace App\Http\Controllers;

use App\Booking;
use App\House;
use App\Http\Requests\SearchRequest;
use App\User;
use Illuminate\Http\Request;
use SebastianBergmann\Comparator\Book;

class SearchController extends Controller
{
    public function index(SearchRequest $request)
    {
        $data = $request->all();

        $houses = House::addSelect(['user_name' => User::select('name')->whereColumn('user_id', 'users.id')])
            ->where('city', 'like', "%{$data['where']}%")
            ->where('places', '>=', $data['people'])
            ->orderBy('name')
            ->orderBy('user_name')
            ->get();

        session([
            'arrival' => $data['arrival'],
            'departure' => $data['departure'],
            'people' => $data['people'],
        ]);

        // эта же проверка должна быть в HousesController@show на $isFree, но с !
        $ids = [];
        foreach ($houses as $house) {
            // найти лишее, уже бронь на эти даты
            if ($house->bookings()
                ->where('status', '=', Booking::STATUS_BOOKING_ACCEPT)
                ->where(function ($query){
                    $query
                    ->whereBetween('departure', [session('arrival'), session('departure')])
                    ->orWhereBetween('arrival', [session('arrival'), session('departure')]);
                })
                ->exists()
            )
                array_push($ids, $house->id);
        }
        // исключить
        $houses = $houses->whereNotIn('id', $ids);
        $houses = $houses->load('user');

        return view('search', [
            'houses' => $houses,
            'where' => $data['where'],
            'arrival' => $data['arrival'],
            'departure' => $data['departure'],
            'people' => $data['people']
        ]);
    }
}

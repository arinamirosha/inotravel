<?php

namespace App\Http\Controllers;

use App\Booking;
use App\House;
use App\User;
use Illuminate\Http\Request;
use SebastianBergmann\Comparator\Book;

class SearchController extends Controller
{
    public function index()
    {
        $data = request()->validate([
            'where' => ['required', 'string', 'max:255'],
            'arrival' => ['required', 'date', 'after:yesterday'],
            'departure' => ['required', 'date', 'after:arrival'],
            'people' => ['required', 'numeric', 'max:100'],
        ]);

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

        // эта же проверка должна быть в HousesController@show на $isFree, но без !
        $ids = [];
        foreach ($houses as $house) {
            // найти лишние
            if (! $house->bookings()

                // уже забронированы
                ->where('status','=','1')
                ->where(function ($query){
                    $query
                    ->where('new','=','1')
                    ->orWhereNull('new');
                })

                // на эти даты
                ->where(function ($query){
                    $query
                    ->whereBetween('departure', [session('arrival'), session('departure')])
                    ->orWhereBetween('arrival', [session('arrival'), session('departure')]);
                })

                ->get()->isEmpty()
            )
                array_push($ids, $house->id);
        }
        // исключить
        $houses = $houses->whereNotIn('id', $ids);

        return view('search', [
            'houses' => $houses,
            'where' => $data['where'],
            'arrival' => $data['arrival'],
            'departure' => $data['departure'],
            'people' => $data['people'],
            // используется ли? пересмотреть, исправить
            'data' => $data
        ]);
    }
}

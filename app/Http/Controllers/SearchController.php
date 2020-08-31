<?php

namespace App\Http\Controllers;

use App\House;
use App\User;
use Illuminate\Http\Request;

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
        ]);

        return view('search', [
            'houses' => $houses,
            'where' => $data['where'],
            'arrival' => $data['arrival'],
            'departure' => $data['departure'],
            'people' => $data['people'],
            'data' => $data
        ]);
    }
}

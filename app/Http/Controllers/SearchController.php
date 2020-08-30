<?php

namespace App\Http\Controllers;

use App\House;
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

        $houses = House::where('city', 'like', "%{$data['where']}%")
            ->where('places', '>=', $data['people'])->latest()->get();

        return view('search', [
            'houses' => $houses,
            'where' => $data['where'],
            'arrival' => $data['arrival'],
            'departure' => $data['departure'],
            'people' => $data['people']
        ]);
    }
}

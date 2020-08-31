<?php

namespace App\Http\Controllers;

use App\Booking;
use App\House;
use Illuminate\Http\Request;
use SebastianBergmann\Comparator\Book;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // if not auth, go to register
    }

    public function store(House $house)
    {
        auth()->user()->bookings()->create([
            'house_id' => $house->id,
            'arrival' => session('arrival'),
            'departure' => session('departure'),
            'people' => session('people')
        ]);
        return redirect(route('house.show', $house->id));
    }

    public function index()
    {
        $user_id = auth()->user()->id;
        $bookings = Booking::where('user_id', '=', $user_id)->latest()->get();
        return view('booking.index', compact('bookings'));
    }
}

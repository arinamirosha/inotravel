<?php

namespace App\Http\Controllers;

use App\Booking;
use App\House;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\Request;
use SebastianBergmann\Comparator\Book;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // if not auth, go to register
    }

    public function store()
    {
        $house_id = request('house_id');
        auth()->user()->bookings()->create([
            'house_id' => $house_id,
            'arrival' => session('arrival'),
            'departure' => session('departure'),
            'people' => session('people')
        ]);
        return redirect(route('house.show', $house_id));
    }

    public function index()
    {
        $user_id = auth()->user()->id;
        $bookings = Booking::where('user_id', '=', $user_id)
            ->where(function ($query){
                $query
                    ->where('new', '=', 1)
                    ->orWhereNull('new');
            })
            ->orderBy('updated_at','desc')->get();
        return view('booking.index', compact('bookings'));
    }

    public function update(Booking $booking)
    {
        if (request()->has('accept')) {
            $newStatus = request('accept');
            $booking->update(['status' => $newStatus, 'new' => true]);
        }
        elseif (request()->has('cancel')) {
            $booking->update(['new' => 0]);
        }
        else {
            $booking->update(['new' => null]);
        }
        return redirect()->back();
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->back();
    }
}

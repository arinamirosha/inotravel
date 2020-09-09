<?php

namespace App\Http\Controllers;

use App\Booking;
use Illuminate\Support\Facades\Auth;
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

    public function store(Request $request)
    {
        $house_id = $request->house_id;
        $user = Auth::user();
        $user->bookings()->create([
            'house_id' => $house_id,
            'arrival' => session('arrival'),
            'departure' => session('departure'),
            'people' => session('people'),
            'status' => Booking::STATUS_BOOKING_SEND
        ]);
        return redirect(route('house.show', $house_id));
//        return redirect()->back();
    }

    public function index()
    {
        $user = Auth::user();
        $bookings = Booking::where('user_id', '=', $user->id)
            ->where('status', '<>', Booking::STATUS_BOOKING_CANCEL)
            ->orderBy('updated_at','desc')
            ->orderBy('created_at','desc')
            ->get();
        return view('booking.index', compact('bookings'));
    }

    public function update(Booking $booking, Request $request)
    {
        if ($request->has('answer')) {
            $newStatus = $request->answer;
            $booking->update(['status' => $newStatus, 'new' => Booking::STATUS_BOOKING_NEW]);
        }
        elseif ($request->has('cancel')) {
            $booking->update(['status' => Booking::STATUS_BOOKING_CANCEL]);
        }
        else {
            $booking->update(['new' => Booking::STATUS_BOOKING_VIEWED]);
        }
        return redirect()->back();
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->back();
    }
}

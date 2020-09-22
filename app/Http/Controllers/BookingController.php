<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Jobs\SendBookingChangedEmail;
use Illuminate\Support\Facades\Auth;
use App\House;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use SebastianBergmann\Comparator\Book;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // if not auth, go to register
    }

    public function store(Request $request)
    {
        $houseId = $request->houseId;
        $house = House::find($houseId);

        if ( ! $house) {
            return redirect(route('welcome'));
        }

        $arrival = $request->arrival;
        $departure = $request->departure;
        $people = $request->people;
        $user = Auth::user();

        $isFree = $house->isFree($arrival, $departure);

        if ($isFree) {
            $user->bookings()->create([
                'house_id' => $houseId,
                'arrival' => $arrival,
                'departure' => $departure,
                'people' => $people,
                'status' => Booking::STATUS_BOOKING_SEND,
            ]);
        }

        return redirect(route('house.show', $houseId));
    }

    public function index()
    {
        $userId = Auth::id();
        $bookings = Booking::where('user_id', '=', $userId)
            ->where('status', '<>', Booking::STATUS_BOOKING_CANCEL)
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->with(['house', 'house.user'])
            ->get();

        return view('booking.index', compact('bookings'));
    }

    public function update(Booking $booking, Request $request)
    {
        $booking->load('user', 'house', 'house.user');
        $status = $request->status;

        switch ($status) {
            case Booking::STATUS_BOOKING_ACCEPT:
            case Booking::STATUS_BOOKING_REJECT:
                $booking->update(['status' => $status, 'new' => Booking::STATUS_BOOKING_NEW]);
                SendBookingChangedEmail::dispatch($booking->user->email, $booking)->delay(now()->addSeconds(10));
                break;

            case Booking::STATUS_BOOKING_CANCEL:
                $booking->update(['status' => $status]);
                SendBookingChangedEmail::dispatch($booking->house->user->email, $booking)->delay(now()->addSeconds(10));
                break;

            case Booking::STATUS_BOOKING_DELETE:
                $booking->delete();
                break;

            case Booking::STATUS_BOOKING_VIEWED:
                $booking->update(['new' => $status]);
                break;
        }

        return redirect()->back();
    }

}

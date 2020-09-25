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
    /**
     * BookingController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Create a new booking if house exists and free
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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

        $isFree = $house->isFree($arrival, $departure, $people);

        if ($isFree) {
            $user->bookings()->create([
                'house_id' => $houseId,
                'arrival' => $arrival,
                'departure' => $departure,
                'people' => $people,
                'status' => Booking::STATUS_BOOKING_SEND,
                'new' => Booking::STATUS_BOOKING_VIEWED,
            ]);
        }

        return redirect(route('house.show', $houseId));
    }

    /**
     * Show all sent bookings excluding cancelled
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * Set new status of booking or delete. Create jobs to send emails about changing of the status
     *
     * @param Booking $booking
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
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

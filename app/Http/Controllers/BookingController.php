<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingHistory;
use App\Events\BookingAnswerEvent;
use App\Events\BookingCancelledEvent;
use App\Events\BookingDeletedEvent;
use App\Events\BookingSentBackEvent;
use App\Events\NewBookingEvent;
use App\Events\BookingStatusChangedEvent;
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

        $arrival = $request->arrival;
        $departure = $request->departure;
        $people = $request->people;

        if ($house && $house->isFree($arrival, $departure, $people)) {
            event(new NewBookingEvent($houseId, $arrival, $departure, $people));
        }

        return back();
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
            ->where('status', '<>', Booking::STATUS_BOOKING_SEND_BACK)
            ->orderBy('updated_at', 'desc')
            ->with(['house', 'house.user'])
            ->paginate(15);

        return view('booking.index', compact('bookings'));
    }

    /**
     * Show history of booking's events
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history()
    {
        $userId = Auth::id();

        $histories = BookingHistory::where('user_id', '=', $userId)
            ->orderBy('created_at', 'desc')
            ->with(['booking', 'booking.user', 'booking.house', 'booking.house.user'])
            ->paginate(15);

        return view('booking.history', compact('histories'));

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
                event(new BookingAnswerEvent($booking, $status));
                break;

            case Booking::STATUS_BOOKING_CANCEL:
                $booking->update(['status' => $status, 'new' => Booking::STATUS_BOOKING_NEW]);
                SendBookingChangedEmail::dispatch($booking->house->user->email, $booking)->delay(now()->addSeconds(10));
                event(new BookingCancelledEvent($booking));
                break;

            case Booking::STATUS_BOOKING_SEND_BACK:
                event(new BookingSentBackEvent($booking));
                $booking->delete();
                break;

            case Booking::STATUS_BOOKING_DELETE:
                $booking->delete();
                break;

            case Booking::STATUS_BOOKING_VIEWED:
                $booking->update(['new' => $status]);
                break;
        }

        return back();
    }

}

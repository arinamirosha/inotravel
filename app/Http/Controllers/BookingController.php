<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingHistory;
use App\Events\BookingAnswerEvent;
use App\Events\BookingCancelledEvent;
use App\Events\HouseDeletedEvent;
use App\Events\BookingSentBackEvent;
use App\Events\NewBookingEvent;
use App\Events\BookingStatusChangedEvent;
use App\Http\Requests\HistoryFilterRequest;
use App\Jobs\SendBookingChangedEmail;
use App\Libraries\BookingHistory\Facades\BookingHistoryManager;
use Illuminate\Support\Facades\Auth;
use App\House;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use SebastianBergmann\Comparator\Book;
use Symfony\Component\Console\Input\Input;

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
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $houseId = $request->houseId;
        $house   = House::find($houseId);

        $arrival   = $request->arrival;
        $departure = $request->departure;
        $people    = $request->people;

        if ($house && $house->isFree($arrival, $departure, $people)) {
            event(new NewBookingEvent($houseId, $arrival, $departure, $people));
        }

        return back();
    }

    /**
     * Show outgoing bookings (except cancelled)
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $userId   = Auth::id();
        $bookings = Booking::where('user_id', '=', $userId)
                           ->where('status', '<>', Booking::STATUS_BOOKING_CANCEL);

        $select = $request->select;
        if ($select && $select != Booking::STATUS_BOOKING_ALL) {
            $bookings = $bookings->where('status', '=', $select);
        }

        $bookings = $bookings->orderBy('updated_at', 'desc')
                             ->with(['house', 'house.user'])
                             ->paginate(15);

        if ($request->ajax()) {
            return view('booking.applications', compact('bookings'));
        }

        return view('booking.index', compact('bookings'));
    }

    /**
     * Apply filters to booking history or show full history (first page load)
     *
     * @param HistoryFilterRequest $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history(HistoryFilterRequest $request)
    {
        $userId = Auth::id();

        if ($request->has('clearHistory')) {
            BookingHistory::where('user_id', '=', $userId)->delete();
        }

        if ($request->ajax()) {
            $histories = $request->statuses ? BookingHistoryManager::getFilteredHistory($userId, $request) : [];

            return view('booking.history_result', compact('histories'));
        } else {
            $histories = BookingHistory::where('user_id', '=', $userId)
                                       ->orderBy('created_at', 'desc')
                                       ->with(['booking', 'booking.user', 'booking.house', 'booking.house.user'])
                                       ->paginate(15);

            return view('booking.history', compact('histories'));
        }
    }

    /**
     * Execute event for booking (answer, cancelled, sent back), soft delete booking or mark as viewed
     *
     * @param Booking $booking
     * @param Request $request
     *
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
                event(new BookingCancelledEvent($booking, $status));
                break;

            case Booking::STATUS_BOOKING_SEND_BACK:
                event(new BookingSentBackEvent($booking));
                break;

            case Booking::STATUS_BOOKING_DELETE:
                $booking->delete();
                break;
        }

        return back();
    }

    /**
     * Make bookings viewed by ajax
     *
     * @param Request $request
     */
    public function viewed(Request $request)
    {
        $user = Auth::user();

        switch ($request->page) {
            case 'bookings':
                $user->bookings()
                     ->where('new', '=', Booking::STATUS_BOOKING_NEW)
                     ->whereIn('status', [Booking::STATUS_BOOKING_ACCEPT, Booking::STATUS_BOOKING_REJECT])
                     ->update(['new' => Booking::STATUS_BOOKING_VIEWED]);
                break;
            case 'houses':
                $houses = $user->houses()->get();
                foreach ($houses as $house) {
                    $house->bookings()
                          ->where('new', '=', Booking::STATUS_BOOKING_NEW)
                          ->whereIn('status', [Booking::STATUS_BOOKING_CANCEL, Booking::STATUS_BOOKING_SEND])
                          ->update(['new' => Booking::STATUS_BOOKING_VIEWED]);
                }
                break;
        }

        return true;
    }

}

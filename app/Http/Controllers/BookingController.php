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
    public function history(Request $request)
    {
        $userId = Auth::id();

        if ($request->has('clearHistory')) {
            BookingHistory::where('user_id', '=', $userId)->delete();
        }

        $histories = BookingHistory::where('user_id', '=', $userId)
            ->orderBy('created_at', 'desc')
            ->with(['booking', 'booking.user', 'booking.house', 'booking.house.user'])
            ->paginate(15);

        if ($request->ajax()) {
            return view('booking.history_result', compact('histories'));
        }

        return view('booking.history', compact('histories'));
    }

    /**
     * Apply filters to history
     *
     * @param HistoryFilterRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filter(HistoryFilterRequest $request)
    {
        $requestData = $request->all();
        $userId = Auth::id();
        $histories = BookingHistory::where('booking_histories.user_id', '=', $userId)
            ->leftJoin('bookings', 'booking_id', '=', 'bookings.id')
            ->leftJoin('houses', 'bookings.house_id', '=', 'houses.id')
        ;

        if ($requestData['city']) {
            $histories = $histories->where('city', 'like', "%" . $requestData['city'] . "%");
        }
        if ($requestData['arrival']) {
            $histories = $histories->where('arrival', '=', $requestData['arrival']);
        }
        if ($requestData['departure']) {
            $histories = $histories->where('departure', '=', $requestData['departure']);
        }

        switch ($requestData['searchAppsHouses']) {
            case BookingHistory::MY_ACCOMMODATION:
                $histories = $histories->where('houses.user_id', '=', $userId);
                break;
            case BookingHistory::MY_APPLICATIONS:
                $histories = $histories->where('bookings.user_id', '=', $userId);
                break;
        }

        if ($request->has('statuses')) {
            $arr=[];
            foreach ($requestData['statuses'] as $status) {
                switch ($status) {
                    case Booking::STATUS_BOOKING_SEND:
                        array_push($arr, BookingHistory::TYPE_SENT, BookingHistory::TYPE_RECEIVED);
                        break;
                    case Booking::STATUS_BOOKING_ACCEPT:
                        array_push($arr, BookingHistory::TYPE_ACCEPTED, BookingHistory::TYPE_ACCEPTED_ANSWER);
                        break;
                    case Booking::STATUS_BOOKING_REJECT:
                        array_push($arr, BookingHistory::TYPE_REJECTED, BookingHistory::TYPE_REJECTED_ANSWER);
                        break;
                    case Booking::STATUS_BOOKING_CANCEL:
                        array_push($arr, BookingHistory::TYPE_CANCELLED, BookingHistory::TYPE_CANCELLED_INFO);
                        break;
                    case Booking::STATUS_BOOKING_SEND_BACK:
                        array_push($arr, BookingHistory::TYPE_SENT_BACK, BookingHistory::TYPE_SENT_BACK_INFO);
                        break;
                    case Booking::STATUS_BOOKING_DELETE:
                        array_push($arr, BookingHistory::TYPE_DELETED, BookingHistory::TYPE_DELETED_INFO);
                        break;
                }
            }
            $histories = $histories->whereIn('type', $arr);
        }

        switch ($requestData['searchOutIn']) {
            case BookingHistory::OUTGOING:
                $histories = $histories->whereIn('type', [
                    BookingHistory::TYPE_SENT,
                    BookingHistory::TYPE_ACCEPTED,
                    BookingHistory::TYPE_REJECTED,
                    BookingHistory::TYPE_CANCELLED,
                    BookingHistory::TYPE_SENT_BACK,
                    BookingHistory::TYPE_DELETED,
                ]);
                break;
            case BookingHistory::INCOMING:
                $histories = $histories->whereIn('type', [
                    BookingHistory::TYPE_RECEIVED,
                    BookingHistory::TYPE_ACCEPTED_ANSWER,
                    BookingHistory::TYPE_REJECTED_ANSWER,
                    BookingHistory::TYPE_CANCELLED_INFO,
                    BookingHistory::TYPE_SENT_BACK_INFO,
                    BookingHistory::TYPE_DELETED_INFO,
                ]);
                break;
        }

        $histories = $histories
            ->with(['booking', 'booking.user', 'booking.house', 'booking.house.user'])
            ->select('booking_histories.*')
            ->orderBy('booking_histories.created_at', 'desc')
            ->paginate(15);

        return view('booking.history_result', compact('histories'));
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
                event(new BookingCancelledEvent($booking, $status));
                break;

            case Booking::STATUS_BOOKING_SEND_BACK:
                event(new BookingSentBackEvent($booking));
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

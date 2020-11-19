<?php


namespace App\Libraries\BookingHistory;


use App\Booking;
use App\BookingHistory;

class BookingHistoryManager
{
    public function getFilteredHistory($userId, $request)
    {
        $requestData = $request->all();

        $histories = BookingHistory::where('booking_histories.user_id', '=', $userId)
            ->leftJoin('bookings', 'booking_id', '=', 'bookings.id')
            ->leftJoin('houses', 'bookings.house_id', '=', 'houses.id');

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
            $arr = [];
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

        return $histories;
    }
}

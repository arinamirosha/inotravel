@switch($history->type)
    @case(\App\BookingHistory::TYPE_SENT)
        {{ $history->booking->house->user->name }} {{ $history->booking->house->user->surname }}
        @break
    @case(\App\BookingHistory::TYPE_RECEIVED)
    @case(\App\BookingHistory::TYPE_ACCEPTED_ANSWER)
    @case(\App\BookingHistory::TYPE_REJECTED_ANSWER)
    @case(\App\BookingHistory::TYPE_CANCELLED_INFO)
    @case(\App\BookingHistory::TYPE_SENT_BACK_INFO)
    @case(\App\BookingHistory::TYPE_DELETED_INFO)
        &ndash;
        @break
    @case(\App\BookingHistory::TYPE_ACCEPTED)
    @case(\App\BookingHistory::TYPE_REJECTED)
    @case(\App\BookingHistory::TYPE_DELETED)
        {{ $history->booking->user->name }} {{ $history->booking->user->surname }}
        @break
    @case(\App\BookingHistory::TYPE_CANCELLED)
    @case(\App\BookingHistory::TYPE_SENT_BACK)
        {{ $history->booking->house->user->name }} {{ $history->booking->house->user->surname }}
        @break
@endswitch

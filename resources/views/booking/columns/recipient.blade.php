@switch($history->type)
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
        <a class="text-dark" href="{{ route('profile.show', $history->booking->user->id) }}">
            {{ $history->booking->user->name }} {{ $history->booking->user->surname }}
        </a>
        @break
    @case(\App\BookingHistory::TYPE_SENT)
    @case(\App\BookingHistory::TYPE_CANCELLED)
    @case(\App\BookingHistory::TYPE_SENT_BACK)
        <a class="text-dark" href="{{ route('profile.show', $history->booking->house->user->id) }}">
            {{ $history->booking->house->user->name }} {{ $history->booking->house->user->surname }}
        </a>
        @break
@endswitch

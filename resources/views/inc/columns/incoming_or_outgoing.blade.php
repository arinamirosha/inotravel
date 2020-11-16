@if(in_array($history->type, [
    \App\BookingHistory::TYPE_SENT,
    \App\BookingHistory::TYPE_ACCEPTED,
    \App\BookingHistory::TYPE_REJECTED,
    \App\BookingHistory::TYPE_CANCELLED,
    \App\BookingHistory::TYPE_SENT_BACK,
    \App\BookingHistory::TYPE_DELETED,
    ]))
    {{ __('Outgoing') }}
@else
    {{ __('Incoming') }}
@endif

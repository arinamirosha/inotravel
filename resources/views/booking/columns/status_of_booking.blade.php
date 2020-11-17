@switch($history->type)
    @case(\App\BookingHistory::TYPE_SENT)
    @case(\App\BookingHistory::TYPE_RECEIVED)
    <div class="text-secondary">{{ __('Sent') }}</div>
    @break
    @case(\App\BookingHistory::TYPE_ACCEPTED)
    @case(\App\BookingHistory::TYPE_ACCEPTED_ANSWER)
    <div class="text-success">{{ __('Accepted') }}</div>
    @break
    @case(\App\BookingHistory::TYPE_REJECTED)
    @case(\App\BookingHistory::TYPE_REJECTED_ANSWER)
    <div class="text-danger">{{ __('Declined') }}</div>
    @break
    @case(\App\BookingHistory::TYPE_CANCELLED)
    @case(\App\BookingHistory::TYPE_CANCELLED_INFO)
    <div class="text-danger">{{ __('Cancelled') }}</div>
    @break
    @case(\App\BookingHistory::TYPE_SENT_BACK)
    @case(\App\BookingHistory::TYPE_SENT_BACK_INFO)
    <div class="text-secondary">{{ __('Sent back') }}</div>
    @break
    @case(\App\BookingHistory::TYPE_DELETED)
    @case(\App\BookingHistory::TYPE_DELETED_INFO)
    <div class="text-danger">{{ __('Deleted') }}</div>
    @break
@endswitch

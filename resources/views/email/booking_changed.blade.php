@component('mail::message')
@component('mail::panel')
    <div>
        {{ __('New application status') }}
    </div>
    <div>
        {{ __('from') }} <b>{{$booking->arrival}}</b> {{ __('to') }} <b>{{$booking->departure}}</b>:
    </div>
    <div class="font-weight-bold m-3">
        @switch($booking->status)
            @case(\App\Booking::STATUS_BOOKING_ACCEPT){{ __('ACCEPTED') }}@break
            @case(\App\Booking::STATUS_BOOKING_REJECT){{ __('DECLINED') }}@break
            @case(\App\Booking::STATUS_BOOKING_CANCEL){{ __('CANCELLED') }}@break
            @case(\App\Booking::STATUS_BOOKING_SEND_BACK){{ __('WITHDRAWN') }}@break
        @endswitch
    </div>
@endcomponent
@component('mail::panel')
    {{ __('Accommodation name') }}: <b>{{$booking->house->name}}</b>
@endcomponent
@component('mail::panel')
    {{ __('City') }}: <b>{{$booking->house->city}}</b>
@endcomponent
@endcomponent

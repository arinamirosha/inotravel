@forelse($bookings as $booking)
    <div class="row h5 pb-1 pt-2 @if ($booking->new == \App\Booking::STATUS_BOOKING_NEW) bg-new @endif">

        <div class="col-md-2">
            <a href="{{ route('house.show', $booking->house->id) }}">
                <img src="{{ url($booking->house->houseImage()) }}" alt="" class="w-100 rounded">
            </a>
        </div>

        <div class="col-md-3 text-left">
            <div>
                <a href="{{ route('house.show', $booking->house->id) }}">{{ $booking->house->name }}</a>
            </div>
            <div>
                {{ $booking->house->city }}
            </div>
            <div>
                {{ $booking->house->user->name }} {{ $booking->house->user->surname }}
            </div>
            <div>
                {{ Carbon\Carbon::parse($booking->arrival)->format('d/m/y') }} - {{ Carbon\Carbon::parse($booking->departure)->format('d/m/y') }}
            </div>
            <div>
                ({{ __('status') }} {{ Carbon\Carbon::parse($booking->updated_at)->format('d/m/y') }})
            </div>
        </div>

        <div class="col-md-3">
            @switch($booking->status)
                @case(\App\Booking::STATUS_BOOKING_ACCEPT)<div class="text-success">{{ __('Application accepted!') }}</div>@break
                @case(\App\Booking::STATUS_BOOKING_SEND)<div class="text-secondary">{{ __('Application has been sent!') }}</div>@break
                @case(\App\Booking::STATUS_BOOKING_REJECT)<div class="text-danger">{{ __('Application declined!') }}</div>@break
            @endswitch
        </div>

        <div class="col-md-2">
            <form method="post" action="{{ route('booking.update', $booking->id) }}">
                @csrf

                @if($booking->new === \App\Booking::STATUS_BOOKING_NEW)
                    <input type="hidden" name="status" value="{{\App\Booking::STATUS_BOOKING_VIEWED}}">
                    <button class="btn btn-sm btn-outline-primary ml-5">{{ __('Ok (new)') }}</button>
                @else
                    @switch($booking->status)

                        @case(\App\Booking::STATUS_BOOKING_ACCEPT)
                        <input type="hidden" name="status" value="{{\App\Booking::STATUS_BOOKING_CANCEL}}">
                        <button class="btn btn-sm btn-outline-danger ml-5" onclick="return confirm('{{ __('Are you sure you want to cancel the application?') }}')">
                            {{ __('Cancel') }}
                        </button>
                        @break

                        @case(\App\Booking::STATUS_BOOKING_SEND)
                        <input type="hidden" name="status" value="{{\App\Booking::STATUS_BOOKING_SEND_BACK}}">
                        <button class="btn btn-sm btn-outline-danger ml-5" onclick="return confirm('{{ __('Are you sure you want to withdraw your application?') }}')">
                            {{ __('Withdraw') }}
                        </button>
                        @break

                        @case(\App\Booking::STATUS_BOOKING_REJECT)
                        <input type="hidden" name="status" value="{{\App\Booking::STATUS_BOOKING_DELETE}}">
                        <button class="btn btn-sm btn-outline-secondary ml-5">
                            {{ __('Hide') }}
                        </button>
                        @break

                    @endswitch
                @endif
            </form>
        </div>

        <div class="col-2">
            {{ __('People') }}: {{ $booking->people }}
        </div>

    </div>

@empty
    <div class="row justify-content-center">
        <div class="col-md-12 p-5 h2">
            {{ __('You have not sent any applications yet!') }}
        </div>
    </div>
@endforelse

<div class="row offset-1">
    <div class="col-6">
        {{$bookings->links()}}
    </div>
</div>

@forelse($bookings as $booking)
    <div class="row pb-1 pt-2 h5 @if ($booking->new == \App\Booking::STATUS_BOOKING_NEW && $booking->status != \App\Booking::STATUS_BOOKING_ACCEPT) bg-new @endif">

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
                {{ __('Application from') }}:
                <a class="text-dark" href="{{ route('profile.show', $booking->user->id) }}">
                    {{ $booking->user->name }} {{ $booking->user->surname }}
                </a>
            </div>
            <div>
                {{ Carbon\Carbon::parse($booking->arrival)->format('d/m/y') }} - {{ Carbon\Carbon::parse($booking->departure)->format('d/m/y') }}
            </div>
        </div>

        <div class="col-md-3">
            @switch($booking->status)

                @case(\App\Booking::STATUS_BOOKING_SEND)
                <form method="post" action="{{ route('booking.update', $booking->id) }}">
                    @csrf
                    <input type="hidden" name="status" id="status{{ $booking->id }}" value="{{ \App\Booking::STATUS_BOOKING_REJECT }}">
                    <button class="btn btn-outline-success" onclick="$('#status{{ $booking->id }}').val({{ \App\Booking::STATUS_BOOKING_ACCEPT }})">
                        {{ __('Accept') }}
                    </button>
                    <button class="btn btn-outline-danger">
                        {{ __('Refuse') }}
                    </button>
                </form>
                @break

                @case(\App\Booking::STATUS_BOOKING_CANCEL)<div class="text-danger">{{ __('Application canceled!') }}</div>@break
                @case(\App\Booking::STATUS_BOOKING_ACCEPT)<div class="text-success">{{ __('Application accepted!') }}</div>@break

            @endswitch
        </div>

        <div class="col-md-2">
            <form method="post" action="{{ route('booking.update', $booking->id) }}">
                @csrf
                @if($booking->status <> \App\Booking::STATUS_BOOKING_SEND && $booking->status <> \App\Booking::STATUS_BOOKING_ACCEPT)
                    <input type="hidden" name="status" value="{{ \App\Booking::STATUS_BOOKING_DELETE }}">
                    <button class="btn btn-sm btn-outline-secondary ml-5">
                        {{ __('Hide') }}
                    </button>
                @endif
            </form>
        </div>

        <div class="col-2">
            {{ __('People') }}: {{ $booking->people }}
        </div>

    </div>

@empty
    <div class="row justify-content-center">
        <div class="col-md-12 p-3 h4">
            {{ __('No applications') }}
        </div>
    </div>
@endforelse

<div class="row offset-1">
    <div class="col-6">
        {{ $bookings->links() }}
    </div>
</div>

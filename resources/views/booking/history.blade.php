@extends('layouts.app')

@section('title')
    {{ __('Applications') }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row text-center">
            <div class="col-md-12">

                @forelse($bookings as $booking)
                    <div class="row p-1 h5 @if($booking->user_id == \Illuminate\Support\Facades\Auth::id()) outgoing @else incoming @endif">

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
                            @if($booking->trashed())
                                {{ __('Deleted') }}
                            @else
                                @switch($booking->status)
                                    @case(\App\Booking::STATUS_BOOKING_ACCEPT)<div class="text-success">{{ __('Application accepted!') }}</div>@break
                                    @case(\App\Booking::STATUS_BOOKING_SEND)<div class="text-secondary">{{ __('Waiting for an answer!') }}</div>@break
                                    @case(\App\Booking::STATUS_BOOKING_REJECT)<div class="text-danger">{{ __('Application declined!') }}</div>@break
                                    @case(\App\Booking::STATUS_BOOKING_CANCEL)<div class="text-danger">{{ __('Application canceled!') }}</div>@break
                                @endswitch
                            @endif
                        </div>

                        <div class="col-md-2">
{{--                            <form method="post" action="{{ route('booking.update', $booking->id) }}">--}}
{{--                                @csrf--}}
{{--                                <input type="hidden" name="status" value="{{\App\Booking::STATUS_BOOKING_DELETE}}">--}}
{{--                                <button class="btn btn-sm btn-outline-danger ml-5" onclick="return confirm('{{ __('Are you sure you want to delete the application?') }}')">--}}
{{--                                    {{ __('Delete') }}--}}
{{--                                </button>--}}
{{--                            </form>--}}

                            @if($booking->user_id == \Illuminate\Support\Facades\Auth::id())
                                {{ __('Outgoing') }}
                            @else
                                {{ __('Incoming') }}
                            @endif
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


            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container light-bg">
        <div class="row text-center">
            <div class="col-md-12">

                @if(! $bookings->isEmpty())

                    @foreach($bookings as $booking)
                        <div class="row pb-3 h5">

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
                                    (статус заявки {{ Carbon\Carbon::parse($booking->updated_at)->format('d/m/y') }})
                                </div>
                            </div>

                            <div class="col-md-4 text-right">
                                <form method="post" action="{{ route('booking.update', $booking->id) }}">
                                    @csrf
                                    @switch($booking->status)

                                        @case(\App\Booking::STATUS_BOOKING_ACCEPT)
                                            <span class="text-success">Заявка принята!</span>
                                            @if($booking->new === \App\Booking::STATUS_BOOKING_NEW)
                                                <input type="hidden" name="status" value="{{\App\Booking::STATUS_BOOKING_VIEWED}}">
                                                <button class="btn btn-sm btn-outline-secondary w-25 ml-5">Ок (новое)</button>
                                            @else
                                                <input type="hidden" name="status" value="{{\App\Booking::STATUS_BOOKING_CANCEL}}">
                                                <button class="btn btn-sm btn-outline-secondary w-25 ml-5" onclick="return confirm('Вы уверены, что хотите отменить заявку?')">
                                                    Отменить
                                                </button>
                                            @endif
                                        @break

                                        @case(\App\Booking::STATUS_BOOKING_SEND)
                                        @case(\App\Booking::STATUS_BOOKING_REJECT)
                                            <input type="hidden" name="status" value="{{\App\Booking::STATUS_BOOKING_DELETE}}">
                                            @if($booking->status === \App\Booking::STATUS_BOOKING_SEND)
                                                <span class="text-secondary">Заявка отправлена!</span>
                                                <button class="btn btn-sm btn-outline-secondary w-25 ml-5" onclick="return confirm('Вы уверены, что хотите отозвать заявку?')">
                                                    Отозвать
                                                </button>
                                            @else
                                                <span class="text-danger">Заявка отклонена!</span>
                                                <button class="btn btn-sm btn-outline-secondary w-25 ml-5" onclick="return confirm('Вы уверены, что хотите удалить заявку?')">
                                                    Удалить
                                                </button>
                                            @endif
                                        @break

                                    @endswitch
                                </form>
                            </div>

                            <div class="col-3">
                                Людей: {{ $booking->people }}
                            </div>

                        </div>
                    @endforeach

                        <div class="row offset-1">
                            <div class="col-6">
                                {{$bookings->links()}}
                            </div>
                        </div>

                @else
                    <div class="row justify-content-center">
                        <div class="col-md-12 p-3 h2">
                            Вы еще не отправляли ни одной заявки!
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

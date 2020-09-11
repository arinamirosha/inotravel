@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">

                @if(! $bookings->isEmpty())

                    @foreach($bookings as $booking)
                        <div class="row pb-3">
                            <div class="col-md-2">
                                <div style="width:150px; height: 150px; overflow: hidden">
                                    <a href="{{ route('house.show', $booking->house->id) }}">
                                        <img src="{{ $booking->house->houseImage() }}" alt="" class="h-100">
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 text-left h5">
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
                                    Людей: {{ $booking->people }}
                                </div>

                                @if($booking->status === \App\Booking::STATUS_BOOKING_ACCEPT)
                                    <div class="text-success">Заявка принята!</div>
                                @elseif($booking->status === \App\Booking::STATUS_BOOKING_REJECT)
                                    <div class="text-danger">Заявка отклонена!</div>
                                @else
                                    <div class="text-secondary">Заявка отправлена!</div>
                                @endif

                                <div>
                                    (статус заявки {{ date_format(date_create($booking->updated_at),"d/m/y") }})
                                </div>
                            </div>

                            <div class="col-md-2 text-right">
                                <div class="row">
                                    <div class="col-8">

                                        @if($booking->new === \App\Booking::STATUS_BOOKING_NEW)
                                            <form method="post" action="{{ route('booking.update', $booking->id) }}">
                                                @csrf
                                                NEW!!! <button class="btn btn-sm btn-outline-secondary">Ок</button>
                                            </form>

                                        @elseif($booking->status === \App\Booking::STATUS_BOOKING_ACCEPT)
                                            <form method="post" action="{{ route('booking.update', $booking->id) }}">
                                                @csrf
                                                <input type="hidden" name="cancel">
                                                <button class="btn btn-sm btn-outline-secondary btn-block" onclick="return confirm('Вы уверены, что хотите отменить заявку?')">
                                                    Отменить
                                                </button>
                                            </form>

                                        @elseif($booking->status === \App\Booking::STATUS_BOOKING_REJECT)
                                            <form action="{{ route('booking.destroy', $booking->id) }}" method="post">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-secondary btn-block" onclick="return confirm('Вы уверены, что хотите удалить заявку?')">
                                                    Удалить
                                                </button>
                                            </form>
                                        @endif

                                        @if($booking->status === \App\Booking::STATUS_BOOKING_SEND)
                                            <form action="{{ route('booking.destroy', $booking->id) }}" method="post">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-secondary btn-block" onclick="return confirm('Вы уверены, что хотите отозвать заявку?')">
                                                    Отозвать
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach

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

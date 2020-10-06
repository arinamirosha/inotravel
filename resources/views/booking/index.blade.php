@extends('layouts.app')

@section('title')
    Заявки
@endsection

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

                            <div class="col-md-3">
                                @switch($booking->status)
                                    @case(\App\Booking::STATUS_BOOKING_ACCEPT)<div class="text-success">Заявка принята!</div>@break
                                    @case(\App\Booking::STATUS_BOOKING_SEND)<div class="text-secondary">Заявка отправлена!</div>@break
                                    @case(\App\Booking::STATUS_BOOKING_REJECT)<div class="text-danger">Заявка отклонена!</div>@break
                                @endswitch
                            </div>

                            <div class="col-md-2">
                                <form method="post" action="{{ route('booking.update', $booking->id) }}">
                                    @csrf

                                    @if($booking->new === \App\Booking::STATUS_BOOKING_NEW)
                                        <input type="hidden" name="status" value="{{\App\Booking::STATUS_BOOKING_VIEWED}}">
                                        <button class="btn btn-sm btn-outline-primary ml-5">Ок (новое)</button>
                                    @else
                                        @switch($booking->status)

                                            @case(\App\Booking::STATUS_BOOKING_ACCEPT)
                                                <input type="hidden" name="status" value="{{\App\Booking::STATUS_BOOKING_CANCEL}}">
                                                <button class="btn btn-sm btn-outline-danger ml-5" onclick="return confirm('Вы уверены, что хотите отменить заявку?')">
                                                    Отменить
                                                </button>
                                            @break

                                            @case(\App\Booking::STATUS_BOOKING_SEND)
                                            @case(\App\Booking::STATUS_BOOKING_REJECT)
                                            <input type="hidden" name="status" value="{{\App\Booking::STATUS_BOOKING_DELETE}}">
                                            @if($booking->status === \App\Booking::STATUS_BOOKING_SEND)
                                                <button class="btn btn-sm btn-outline-danger ml-5" onclick="return confirm('Вы уверены, что хотите отозвать заявку?')">
                                                    Отозвать
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline-danger ml-5" onclick="return confirm('Вы уверены, что хотите удалить заявку?')">
                                                    Удалить
                                                </button>
                                            @endif
                                            @break

                                        @endswitch
                                    @endif
                                </form>
                            </div>

                            <div class="col-2">
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
                        <div class="col-md-12 p-5 h2">
                            Вы еще не отправляли ни одной заявки!
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

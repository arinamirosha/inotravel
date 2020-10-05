@extends('layouts.app')

@section('title')
    Мое жилье
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row text-center">
            <div class="col-md-12">

                @if(! $houses->isEmpty())

                    <div class="row pb-2">
                        <div class="col-1 mt-1 h5">
                            Заявки
                        </div>
                        <div class="col-11 mt-3 border-top border-dark">
                        </div>
                    </div>

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
                                        Заявка от: {{ $booking->user->name }} {{ $booking->user->surname }}
                                    </div>
                                    <div>
                                        {{ Carbon\Carbon::parse($booking->arrival)->format('d/m/y') }} - {{ Carbon\Carbon::parse($booking->departure)->format('d/m/y') }}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <form method="post" action="{{ route('booking.update', $booking->id) }}">
                                        @csrf
                                        @switch($booking->status)

                                            @case(\App\Booking::STATUS_BOOKING_SEND)
                                            <input type="hidden" name="status" id="status{{ $booking->id }}" value="{{ \App\Booking::STATUS_BOOKING_REJECT }}">
                                            <button class="btn btn-outline-secondary" onclick="$('#status{{ $booking->id }}').val({{ \App\Booking::STATUS_BOOKING_ACCEPT }})">
                                                Принять
                                            </button>
                                            <button class="btn btn-outline-secondary">
                                                Отказать
                                            </button>
                                            @break

                                            @case(\App\Booking::STATUS_BOOKING_CANCEL)
                                                <div class="text-right">
                                                    <span class="text-danger">Отмена заявки!</span>
                                                    <input type="hidden" name="status" value="{{\App\Booking::STATUS_BOOKING_DELETE}}">
                                                    <button class="btn btn-sm btn-outline-secondary w-25 ml-4" onclick="return confirm('Вы уверены, что хотите удалить заявку?')">
                                                        Удалить
                                                    </button>
                                                </div>
                                            @break

                                            @default
                                            <div class="text-success">Заявка принята!</div>

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
                            <div class="col-md-12 p-3 h4">
                                Входящих заявок нет
                            </div>
                        </div>
                    @endif


                    <div class="row pb-2">
                        <div class="col-1 mt-1 h5">
                            Жилье
                        </div>
                        <div class="col-9 mt-3 border-top border-dark">
                        </div>
                        <div class="col-2">
                            <a href="{{ route('house.create') }}" class="btn btn-outline-dark">Добавить жилье</a>
                        </div>
                    </div>

                    @foreach($houses as $house)
                        <div class="row pb-3">
                            <div class="col-md-2">
                                <a href="{{ route('house.show', $house->id) }}">
                                    <img src="{{ $house->houseImage() }}" alt="" class="w-100 rounded">
                                </a>
                            </div>
                            <div class="col-md-4 text-left h5">
                                <div>
                                    <a href="{{ route('house.show', $house->id) }}">{{ $house->name }}</a>
                                </div>
                                <div>
                                    {{ $house->city }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="row justify-content-center">
                            <div class="col-md-12 p-3 h2">
                                Вы еще не создали ни одного профиля жилья!
                            </div>
                            <div>
                                <a href="{{ route('house.create') }}" class="btn btn-primary btn-lg">Создать</a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endsection

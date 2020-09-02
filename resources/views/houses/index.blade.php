@extends('layouts.app')

@section('content')
    <div class="container">
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
                            <div class="row pb-3">
                                <div class="col-md-2">
                                    <div style="width:150px; height: 150px; overflow: hidden">
                                        <a href="{{ route('house.show', $booking->house->id) }}">
                                            <img src="{{ $booking->house->houseImage() }}" alt="" class="h-100">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 text-left h5">
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
                                        {{ date_format(date_create($booking->arrival),"d/m/y") }} - {{ date_format(date_create($booking->departure),"d/m/y") }}
                                    </div>
                                    <div>
                                        Людей: {{ $booking->people }}
                                    </div>

                                    @if($booking->status === 1)
                                        <div class="text-success">Заявка принята!</div>
                                    @else($booking->status === null)
                                        <form method="post" action="{{ route('booking.update', $booking->id) }}">
                                            @csrf
                                            @method("patch")

                                            <input type="hidden" name="accept" id="accept{{ $booking->id }}" value="0">
                                            <button class="btn btn-outline-secondary" onclick="$('#accept{{ $booking->id }}').val('1')">
                                                Принять
                                            </button>
                                            <button class="btn btn-outline-secondary">
                                                Отказать
                                            </button>

                                        </form>
                                    @endif

                                </div>
                            </div>
                        @endforeach

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
                                <div style="width:150px; height: 150px; overflow: hidden">
                                    <a href="{{ route('house.show', $house->id) }}">
                                        <img src="{{ $house->houseImage() }}" alt="" class="h-100">
                                    </a>
                                </div>
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

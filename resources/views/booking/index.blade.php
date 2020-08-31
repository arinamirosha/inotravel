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
                            <div class="col-md-4 text-left h5">
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
                                    {{ date_format(date_create($booking->arrival),"d/m/y") }} - {{ date_format(date_create($booking->departure),"d/m/y") }}
                                </div>
                                <div>
                                    Людей: {{ $booking->people }}
                                </div>

                                @if($booking->status === 1)
                                    <div class="text-success">Заявка принята!</div>
                                @elseif($booking->status === 0)
                                    <div class="text-danger">Заявка отклонена!</div>
                                @else
                                    <div class="text-secondary">Заявка отправлена!</div>
                                @endif

                                <div>
                                    (статус заявки {{ date_format(date_create($booking->updated_at),"d/m/y") }})
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

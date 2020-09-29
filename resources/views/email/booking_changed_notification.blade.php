@extends('layouts.email')

@section('content')

    <div class="row text-center mt-4">
        <div class="col-12 h3">
            <div>
                Новый статус заявки
            </div>
            <div>
                с <b>{{$booking->arrival}}</b> по <b>{{$booking->departure}}</b>:
            </div>
            <div class="font-weight-bold m-3">
                @switch($booking->status)
                    @case(\App\Booking::STATUS_BOOKING_ACCEPT)ОДОБРЕНО@break
                    @case(\App\Booking::STATUS_BOOKING_REJECT)ОТКАЗ@break
                    @case(\App\Booking::STATUS_BOOKING_CANCEL)ОТМЕНА@break
                @endswitch
            </div>
            <div>
                Название: <b>{{$booking->house->name}}</b>
            </div>
            <div>
                Город: <b>{{$booking->house->city}}</b>
            </div>
        </div>
    </div>

@endsection

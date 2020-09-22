@extends('layouts.email')

@section('content')

    <div class="row">
        <div class="col-8 h3">
            <div>Новый статус заявки с <b>{{$booking->arrival}}</b> по <b>{{$booking->departure}}</b>:
                <b>
                    @switch($booking->status)
                        @case(\App\Booking::STATUS_BOOKING_ACCEPT)ОДОБРЕНО@break
                        @case(\App\Booking::STATUS_BOOKING_REJECT)ОТКАЗ@break
                        @case(\App\Booking::STATUS_BOOKING_CANCEL)ОТМЕНА@break
                    @endswitch
                </b>
            </div>
            <div>Название: <b>{{$booking->house->name}}</b></div>
            <div>Город: <b>{{$booking->house->city}}</b></div>
        </div>
    </div>
@endsection

@component('mail::message')
{{-- Body --}}
@component('mail::panel')
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
@endcomponent
@component('mail::panel')
    Название: <b>{{$booking->house->name}}</b>
@endcomponent
@component('mail::panel')
    Город: <b>{{$booking->house->city}}</b>
@endcomponent
@endcomponent

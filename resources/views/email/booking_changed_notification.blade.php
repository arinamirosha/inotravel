@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot
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
        Название: <b>{{$booking->house->name}}</b><br>
        Город: <b>{{$booking->house->city}}</b>
    @endcomponent
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        @endcomponent
    @endslot
@endcomponent

@component('mail::message')
@component('mail::panel')
    <div>
        {{ __('New application') }}
    </div>
    <div>
        {{ __('from') }} <b>{{$booking->arrival}}</b> {{ __('to') }} <b>{{$booking->departure}}</b>
    </div>
    <div>
        {{ __('People') }}: {{$booking->people}}
    </div>
@endcomponent
@component('mail::panel')
    <div>
        {{ __('Housing') }}
    </div>
    <div>
        {{ __('Accommodation name') }}: <b>{{$booking->house->name}}</b>
    </div>
    <div>
        {{ __('City') }}: <b>{{$booking->house->city}}</b>
    </div>
@endcomponent
@endcomponent

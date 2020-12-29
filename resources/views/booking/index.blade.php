@extends('layouts.app')

@section('title')
    {{ __('Applications') }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row mb-4 mr-3">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <form id="select-form" action="{{route('booking.index')}}" method="get">
                    <select id="select" class="form-control" name="select" aria-label="select">
                        <option value="{{\App\Booking::STATUS_BOOKING_ALL}}">Все</option>
                        <option value="{{\App\Booking::STATUS_BOOKING_SEND}}">Заявка отправлена</option>
                        <option value="{{\App\Booking::STATUS_BOOKING_ACCEPT}}">Заявка принята</option>
                        <option value="{{\App\Booking::STATUS_BOOKING_REJECT}}">Заявка отклонена</option>
                    </select>
                </form>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-md-12" id="result_wrap">

                @include('booking.applications')

            </div>
        </div>
    </div>
@endsection

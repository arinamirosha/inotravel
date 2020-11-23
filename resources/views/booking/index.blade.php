@extends('layouts.app')

@section('title')
    {{ __('Applications') }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row text-center">
            <div class="col-md-12" id="result_wrap">

                @include('booking.applications')

            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title')
    {{ __('Users') }}
@endsection

@section('content')
    <div class="container light-bg">

        @if($notifications->isNotEmpty())
            <div class="row text-right mb-3">
                <div class="col-md-10">

                    <form action="{{ route('admin.mark-as-read') }}" method="post">
                        @csrf
                        <button class="btn btn-outline-dark btn-sm">{{ __('Mark All As Read') }}</button>
                    </form>

                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">

                    @include('admin.notifications')

                </div>
            </div>
            <div class="row border-top border-dark mt-3 mb-4"></div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-10" id="result_wrap">

                @include('admin.users')

            </div>
        </div>
    </div>
@endsection

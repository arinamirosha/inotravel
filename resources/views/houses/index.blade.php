@extends('layouts.app')

@section('title')
    {{ __('My accommodation') }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row text-center">
            <div class="col-md-12">

                @if(!$houses->isEmpty())

                    <div class="row pb-2">
                        <div class="col-1 mt-1 h5">
                            {{ __('Applications') }}
                        </div>
                        <div class="col-11 mt-3 border-top border-dark">
                        </div>
                    </div>

                    <div class="row mb-4 mr-3">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <form id="select-form" action="{{route('house.index')}}" method="get">
                                <select id="select" class="form-control" name="select" aria-label="select">
                                    <option value="{{\App\Booking::STATUS_BOOKING_ALL}}">{{__('All')}}</option>
                                    <option value="{{\App\Booking::STATUS_BOOKING_SEND}}">{{__('Waiting for an answer')}}</option>
                                    <option value="{{\App\Booking::STATUS_BOOKING_ACCEPT}}">{{__('Accepted')}}</option>
                                    <option value="{{\App\Booking::STATUS_BOOKING_CANCEL}}">{{__('Cancelled')}}</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <div id="result_wrap">
                        @include('houses.applications')
                    </div>

                    <div class="row pb-2">
                        <div class="col-1 mt-1 h5">
                            {{ __('Housing') }}
                        </div>
                        <div class="col-9 mt-3 border-top border-dark">
                        </div>
                        <div class="col-2">
                            <a href="{{ route('house.create') }}" class="btn btn-outline-dark w-100">{{ __('Add') }}</a>
                        </div>
                    </div>

                    @foreach($houses as $house)
                        <div class="row pb-3">
                            <div class="col-md-2">
                                <a href="{{ route('house.show', $house->id) }}">
                                    <img src="{{ $house->houseImage() }}" alt="" class="w-100 rounded">
                                </a>
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
                        <div class="col-md-12 p-5 h2">
                            {{ __('You have not created any housing profiles yet!') }}
                        </div>
                        <div>
                            <a href="{{ route('house.create') }}" class="btn btn-primary btn-lg">{{ __('Create') }}</a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

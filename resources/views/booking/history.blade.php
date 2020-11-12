@extends('layouts.app')

@section('title')
    {{ __('History') }}
@endsection

@section('filters')
    <div class="col-md-2 light-bg h5 text-center pt-3" id="filters">

        <div class="font-weight-bold mb-4 h6 p-2 bg-title rounded">{{ __('Filters') }}</div>

        <form action="{{route('booking.filter')}}" method="post" id="filter-form">
            @csrf
            @include('inc.filter_form_elements')

            <button type="submit" class="btn btn-block btn-outline-primary">
                {{__('Apply')}}
            </button>
            <a class="btn btn-block btn-outline-primary" href="#" onclick="return confirm('{{ __('Are you sure you want to clear history?') }}')">
                {{__('Clear history')}}
            </a>

        </form>

    </div>
@endsection

@section('content')
    <div class="col-md-9 light-bg pt-3">
        <div class="row text-center">
            <div class="col-md-12" id="filter-result">

                @include('inc.history_result')

            </div>
        </div>
    </div>
@endsection

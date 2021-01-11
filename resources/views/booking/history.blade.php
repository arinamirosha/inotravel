@extends('layouts.app')

@section('title')
    {{ __('History') }}
@endsection

@section('filters')
    <div class="col-md-2 light-bg h5 text-center pt-3" id="filters">

        <div class="font-weight-bold mb-4 h6 p-2 bg-title rounded">{{ __('Filters') }}</div>

        <form action="{{ route('booking.history') }}" method="get" id="filter-form">
            @include('inc.filter_form_elements')

            <button type="submit" class="btn btn-block btn-primary">
                {{ __('Apply') }}
            </button>
        </form>

        <a class="btn btn-block btn-outline-primary mb-2 mt-2" href="{{ route('booking.history') }}">
            {{ __('Clear all filters') }}
        </a>

        <form action="{{ route('booking.history') }}" method="get">
            <input type="checkbox" name="clearHistory" checked class="d-none">
            <button type="submit" class="btn btn-block btn-outline-primary" onclick="return confirm('{{ __('Are you sure you want to clear history?') }}')">
                {{ __('Clear history') }}
            </button>
        </form>

        <div id="process" class="mt-4">
            <div class="spinner-border" role="status">
                <span class="sr-only">{{ __('Loading...') }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="col-md-9 light-bg pt-3">
        <div class="row text-center">
            <div class="col-md-12" id="filter-result">

                @include('booking.history_result')

            </div>
        </div>
    </div>
@endsection

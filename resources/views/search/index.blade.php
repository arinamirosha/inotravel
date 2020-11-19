@extends('layouts.app')

@section('title')
    {{ __('Search') }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row">
            <div class="col-md-12">

                <form method="get" action="{{ route('search') }}" id="search-form">
                    <div class="row">
                        <div class="col-9">
                            @include('inc.search_form_elements')
                        </div>
                        <div class="col-3 pt-4">
                            <button type="submit" class="btn btn-secondary">{{ __('New search') }}</button>
                        </div>
                    </div>
                </form>

                <div id="search-result">
                    @include('search.houses')
                </div>

            </div>
        </div>
    </div>
@endsection

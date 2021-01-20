@extends('layouts.email')

@section('content')

    <div class="row text-center mt-4">
        <div class="col-12 h3">
            <div>
                {{ __('Approved application') }}
            </div>
            <div>
                {{ __('from') }} <b>{{ $arrival }}</b> {{ __('to') }} <b>{{ $departure }}</b>
            </div>
            <div class="m-3">
                <div class="font-weight-bold h2">
                    {{ __('deleted') }}
                </div>
                <div>
                    {{ __('due to the fact that the owner has removed his home') }}
                </div>
            </div>
            <div>
                {{ __('Accommodation name') }}: <b>{{ $name }}</b>
            </div>
            <div>
                {{ __('City') }}: <b>{{ $city }}</b>
            </div>
        </div>
    </div>

@endsection

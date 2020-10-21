@extends('layouts.app')

@section('title')
    {{ __('Search') }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row">
            <div class="col-md-12">

                <form method="get" action="{{ route('search') }}">
                    <div class="row">
                        <div class="col-9">
                            @include('inc.search_form_elements')
                        </div>
                        <div class="col-3 pt-4">
                            <button class="btn btn-secondary">{{ __('New search') }}</button>
                        </div>
                    </div>
                </form>

                <div class="row mt-3 justify-content-start">
                    @forelse($houses as $house)

                        <div class="mr-3 mb-4 col-2">
                            <div>
                                <a href="{{ route('house.show', $house->id) }}">
                                    <img src="{{ url($house->houseImage()) }}" alt="" class="w-100 rounded">
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('house.show', $house->id) }}">{{ $house->name }}</a>
                            </div>
                            <div>
                                {{ $house->city }}
                            </div>
                            <div>
                                {{ $house->user->name }} {{ $house->user->surname }}
                            </div>
                        </div>

                    @empty
                        <div class="col-md-12 p-3 h5 text-center">
                            {{ __('Not Found') }}
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
@endsection

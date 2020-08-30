@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <form method="get" action="{{ route('search') }}">
                    <div class="row">
                        <div class="col-9">
                            @include('inc.searchFormElements')
                        </div>
                        <div class="col-3 pt-4">
                            <button class="btn btn-outline-secondary">Новый поиск</button>
                        </div>
                    </div>
                </form>

                @if(! $houses->isEmpty())
                    <div class="row float-left mt-2 justify-content-between">
                        @foreach($houses as $house)
                            <div class="mr-4 ml-4 mb-4">
                                <div style="width:150px; height: 150px; overflow: hidden">
                                    <a href="{{ route('house.show', $house->id) }}">
                                        <img src="{{ $house->houseImage() }}" alt="" class="h-100">
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
                        @endforeach
                    </div>
                @else
                    <div class="row justify-content-center">
                        <div class="col-md-12 p-3 h5 text-center">
                            Не найдено
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

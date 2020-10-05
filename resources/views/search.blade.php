@extends('layouts.app')

@section('title')
    Поиск
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
                            <button class="btn btn-secondary">Новый поиск</button>
                        </div>
                    </div>
                </form>

                @if(! $houses->isEmpty())
                    <div class="row mt-3 justify-content-start">
                        @foreach($houses as $house)
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

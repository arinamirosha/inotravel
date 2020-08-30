@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">

                @if(! $houses->isEmpty())

                    <div class="row pb-2">
                        <div class="col-1 mt-1 h5">
                            Жилье
                        </div>
                        <div class="col-9 mt-3 border-top border-dark">
                        </div>
                        <div class="col-2">
                            <a href="{{ route('house.create') }}" class="btn btn-outline-dark">Добавить жилье</a>
                        </div>
                    </div>

                    @foreach($houses as $house)
                        <div class="row pb-3">
                            <div class="col-md-2">
                                <div style="width:150px; height: 150px; overflow: hidden">
                                    <a href="{{ route('house.show', $house->id) }}">
                                        <img src="{{ $house->houseImage() }}" alt="" class="h-100">
                                    </a>
                                </div>
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
                            <div class="col-md-12 p-3 h2">
                                Вы еще не создали ни одного профиля жилья!
                            </div>
                            <div>
                                <a href="{{ route('house.create') }}" class="btn btn-primary btn-lg">Создать</a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endsection

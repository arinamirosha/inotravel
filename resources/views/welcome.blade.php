@extends('layouts.app')

@section('title')
    Главная &ndash; поиск
@endsection

@section('content')
<div class="container light-bg p-5">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center p-3 h1">
            Искать жилье
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-9">
            <form method="get" action="{{ route('search') }}">

                @include('inc.search_form_elements')

                <div class="row justify-content-center pb-4 pt-3 text-center">
                    <button class="btn btn-secondary btn-block w-25">Поиск</button>
                </div>

                <div class="row justify-content-center text-center">
                    @if(! Auth::check() || ! Auth::user()->houses()->exists())
                        <a href="{{ route('house.create') }}" class="btn btn-dark btn-block w-25">Приму гостей!</a>
                    @endif
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

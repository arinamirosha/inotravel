@extends('layouts.app')

@section('content')
<div class="container light-bg">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center p-3 h1">
            Искать жилье
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-9">
            <form method="get" action="{{ route('search') }}">

                @include('inc.search_form_elements')

                <div class="row justify-content-center pb-4 text-center">
                    <div class="col-md-3">
                        <button class="btn btn-secondary">Поиск</button>
                    </div>
                </div>

                <div class="row justify-content-center text-center">
                    <div class="col-md-5">
                        @if(! Auth::check() || ! Auth::user()->houses()->exists())
                            <a href="{{ route('house.create') }}" class="btn btn-primary">Приму гостей!</a>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

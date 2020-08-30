@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center p-3 h1">
            Искать жилье сти
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="where" class="col-form-label text-md-right">{{ __('Куда:') }}</label>
                        <input id="where" type="text" class="form-control @error('where') is-invalid @enderror" name="where" value="{{ old('where') }}" required autocomplete="where" autofocus>

                        @error('where')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="arrival" class="col-form-label text-md-right">{{ __('Прибытие:') }}</label>
                        <input id="arrival" type="date" class="form-control @error('arrival') is-invalid @enderror" name="arrival" value="{{ old('arrival') }}" required autocomplete="arrival" autofocus>

                        @error('arrival')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="departure" class="col-form-label text-md-right">{{ __('Отъезд:') }}</label>
                        <input id="departure" type="date" class="form-control @error('departure') is-invalid @enderror" name="departure" value="{{ old('departure') }}" required autocomplete="departure" autofocus>

                        @error('departure')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="people" class="col-form-label text-md-right">{{ __('Людей:') }}</label>
                        <input id="people" type="text" class="form-control @error('people') is-invalid @enderror" name="people" required autocomplete="current-people">

                        @error('people')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="row justify-content-center pb-4 text-center">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-secondary">Поиск</button>
                    </div>
                </div>

                <div class="row justify-content-center text-center">
                    <div class="col-md-5">
                        @if(! Auth::user() || Auth::user()->housesExist()->isEmpty())
                            <a href="{{ route('house.create') }}" class="btn btn-primary">Приму гостей!</a>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

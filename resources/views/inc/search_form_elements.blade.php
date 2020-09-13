<div class="form-group row">
    <div class="col-md-4">
        <label for="where" class="col-form-label text-md-right">{{ __('Куда:') }}</label>
        <input id="where" type="text" class="form-control @error('where') is-invalid @enderror" name="where" value="{{ $searchData['where'] ?? old('where')  }} " autocomplete="where" autofocus>

        @error('where')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="arrival" class="col-form-label text-md-right">{{ __('Прибытие:') }}</label>
        <input id="arrival" type="date" class="form-control @error('arrival') is-invalid @enderror" name="arrival" value="{{ $searchData['arrival'] ?? old('arrival') }}" autocomplete="arrival" autofocus>

        @error('arrival')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="departure" class="col-form-label text-md-right">{{ __('Отъезд:') }}</label>
        <input id="departure" type="date" class="form-control @error('departure') is-invalid @enderror" name="departure" value="{{ $searchData['departure'] ?? old('departure') }}" autocomplete="departure" autofocus>

        @error('departure')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="col-md-2">
        <label for="people" class="col-form-label text-md-right">{{ __('Людей:') }}</label>
        <input id="people" type="text" class="form-control @error('people') is-invalid @enderror" name="people" value="{{ $searchData['people'] ?? old('people') }}" autocomplete="people">

        @error('people')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

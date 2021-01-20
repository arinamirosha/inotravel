<div class="form-group row">
    <div class="col-md-4">
        <label for="where" class="col-form-label text-md-right">{{ __('Where') }}</label>
        <input id="where" type="text" class="form-control @error('where') is-invalid @enderror" name="where"
               value="@isset($searchData){{ $searchData['where'] }}@else{{ old('where') }}@endisset" autocomplete="where" autofocus>

        @error('where')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <span class="invalid-feedback" role="alert">
            <strong id="errorWhere"></strong>
        </span>
    </div>

    <div class="col-md-3">
        <label for="arrival" class="col-form-label text-md-right">{{ __('Arrival') }}</label>
        <input id="arrival" type="date" class="form-control @error('arrival') is-invalid @enderror" name="arrival"
               value="@isset($searchData){{ $searchData['arrival'] }}@else{{ old('arrival') }}@endisset" autocomplete="arrival" autofocus>

        @error('arrival')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <span class="invalid-feedback" role="alert">
            <strong id="errorArrival"></strong>
        </span>
    </div>

    <div class="col-md-3">
        <label for="departure" class="col-form-label text-md-right">{{ __('Departure') }}</label>
        <input id="departure" type="date" class="form-control @error('departure') is-invalid @enderror" name="departure"
               value="@isset($searchData){{ $searchData['departure'] }}@else{{ old('departure') }}@endisset" autocomplete="departure" autofocus>

        @error('departure')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <span class="invalid-feedback" role="alert">
            <strong id="errorDeparture"></strong>
        </span>
    </div>

    <div class="col-md-2">
        <label for="people" class="col-form-label text-md-right">{{ __('People') }}</label>
        <input id="people" type="number" class="form-control @error('people') is-invalid @enderror" name="people"
               value="@isset($searchData){{ $searchData['people'] }}@else{{ old('people') }}@endisset" autocomplete="people">

        @error('people')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <span class="invalid-feedback" role="alert">
            <strong id="errorPeople"></strong>
        </span>
    </div>
</div>

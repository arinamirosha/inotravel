<div class="form-group row">
    <div class="col-md-4">
        <label for="where" class="col-form-label text-md-right">{{ __('Куда:') }}</label>
        <input id="where" type="text" class="form-control @error('where') is-invalid @enderror" name="where"
               value="@if(isset($searchData)){{old('where',$searchData['where'])}}@else{{old('where')}}@endif" autocomplete="where" autofocus>

        @error('where')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="arrival" class="col-form-label text-md-right">{{ __('Прибытие:') }}</label>
        <input id="arrival" type="date" class="form-control @error('arrival') is-invalid @enderror" name="arrival"
               value="@if(isset($searchData)){{old('arrival',$searchData['arrival'])}}@else{{old('arrival')}}@endif" autocomplete="arrival" autofocus>

        @error('arrival')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="departure" class="col-form-label text-md-right">{{ __('Отъезд:') }}</label>
        <input id="departure" type="date" class="form-control @error('departure') is-invalid @enderror" name="departure"
               value="@if(isset($searchData)){{old('departure',$searchData['departure'])}}@else{{old('departure')}}@endif" autocomplete="departure" autofocus>

        @error('departure')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="col-md-2">
        <label for="people" class="col-form-label text-md-right">{{ __('Людей:') }}</label>
        <input id="people" type="number" class="form-control @error('people') is-invalid @enderror" name="people"
               value="@if(isset($searchData)){{old('people',$searchData['people'])}}@else{{old('people')}}@endif" autocomplete="people">

        @error('people')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label for="name" class="col-md-2 col-form-label-sm text-md-right">{{ __('Название:') }}</label>

    <div class="col-md-4">
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $house->name ?? old('name') }}" autocomplete="name" autofocus>

        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <label for="city" class="col-md-2 col-form-label-sm text-md-right">{{ __('Город:') }}</label>

    <div class="col-md-4">
        <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ $house->city ?? old('city') }}" autocomplete="city" autofocus>

        @error('city')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label for="places" class="col-md-2 col-form-label-sm text-md-right">{{ __('Спальных мест:') }}</label>

    <div class="col-md-4">
        <input id="places" type="number" class="form-control @error('places') is-invalid @enderror" name="places" value="{{ $house->places ?? old('places') }}" autocomplete="places" autofocus>

        @error('places')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <label for="address" class="col-md-2 col-form-label-sm text-md-right">{{ __('Адрес:') }}</label>

    <div class="col-md-4">
        <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ $house->address ?? old('address') }}" autocomplete="address" autofocus>

        @error('address')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <span class="col-md-2 col-form-label-sm text-md-right">{{ __('Удобства:') }}</span>

    <div class="col-md-4">
        <div class="form-check text-left">
            <input class="form-check-input" type="checkbox" value="1" id="internet" name="facilities[]" @if(isset($house)) @if($house->facilities->contains('1')) checked @endif @endif>
            <label class="form-check-label" for="internet">
                Интернет
            </label>
        </div>
        <div class="form-check text-left">
            <input class="form-check-input" type="checkbox" value="2" id="wifi" name="facilities[]" @if(isset($house)) @if($house->facilities->contains('2')) checked @endif @endif>
            <label class="form-check-label" for="wifi">
                Wi-Fi
            </label>
        </div>
        <div class="form-check text-left">
            <input class="form-check-input" type="checkbox" value="3" id="cable_tv" name="facilities[]" @if(isset($house)) @if($house->facilities->contains('3')) checked @endif @endif>
            <label class="form-check-label" for="cable_tv">
                Кабельное ТВ
            </label>
        </div>
        <div class="form-check text-left">
            <input class="form-check-input" type="checkbox" value="4" id="conditioner" name="facilities[]"@if(isset($house)) @if($house->facilities->contains('4')) checked @endif @endif>
            <label class="form-check-label" for="conditioner">
                Кондиционер
            </label>
        </div>
        <div class="form-check text-left">
            <input class="form-check-input" type="checkbox" value="5" id="washer" name="facilities[]" @if(isset($house)) @if($house->facilities->contains('5')) checked @endif @endif>
            <label class="form-check-label" for="washer">
                Стиральная машина
            </label>
        </div>
    </div>

    <span class="col-md-2 col-form-label-sm text-md-right">{{ __('Ограничения:') }}</span>

    <div class="col-md-4">
        <div class="form-check text-left">
            <input class="form-check-input" type="checkbox" value="1" id="animals" name="restrictions[]" @if(isset($house)) @if($house->restrictions->contains('1')) checked @endif @endif>
            <label class="form-check-label" for="animals">
                Животные
            </label>
        </div>
        <div class="form-check text-left">
            <input class="form-check-input" type="checkbox" value="2" id="houseplants" name="restrictions[]" @if(isset($house)) @if($house->restrictions->contains('2')) checked @endif @endif>
            <label class="form-check-label" for="houseplants">
                Комнатные растения
            </label>
        </div>
        <div class="form-check text-left">
            <input class="form-check-input" type="checkbox" value="3" id="no_smoke" name="restrictions[]" @if(isset($house)) @if($house->restrictions->contains('3')) checked @endif @endif>
            <label class="form-check-label" for="no_smoke">
                Нельзя курить
            </label>
        </div>
        <div class="form-check text-left">
            <input class="form-check-input" type="checkbox" value="4" id="no_drink" name="restrictions[]" @if(isset($house)) @if($house->restrictions->contains('4')) checked @endif @endif>
            <label class="form-check-label" for="no_drink">
                Нельзя пить
            </label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label for="info" class="col-md-2 col-form-label-sm text-md-right">{{ __('Доп. инфо:') }}</label>

    <div class="col-md-10">
        <textarea id="info" type="text" rows="5" class="form-control @error('info') is-invalid @enderror" name="info" autocomplete="info" autofocus>{{ $house->info ?? old('info') }}</textarea>

        @error('info')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

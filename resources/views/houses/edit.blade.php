@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row text-center">
            <div class="col-md-11">
                <form method="POST" action="{{ route('house.update', $house->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="row">

                        <div class="col-md-3">
                            <input id="deleteImage" name="deleteImage" type="checkbox" style="display:none">
                            <div id="mydiv" style="word-wrap:break-word"></div>
                            <img src="{{ $house->houseImage() }}" alt="" class="w-100" id="myimage">
                            <div>
                                <upload-image-button></upload-image-button>
                                @error('image')
                                <strong>{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="pt-3">
                                <delete-image-button></delete-image-button>
                            </div>
                        </div>

                        <div class="col-md-9">


                            <div class="form-group row">
                                <label for="name" class="col-md-2 col-form-label-sm text-md-right">{{ __('Название:') }}</label>

                                <div class="col-md-4">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $house->name }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <label for="city" class="col-md-2 col-form-label-sm text-md-right">{{ __('Город:') }}</label>

                                <div class="col-md-4">
                                    <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') ?? $house->city }}" required autocomplete="city" autofocus>

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
                                    <input id="places" type="number" class="form-control @error('places') is-invalid @enderror" name="places" value="{{ old('places') ?? $house->places }}" required autocomplete="places" autofocus>

                                    @error('places')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <label for="address" class="col-md-2 col-form-label-sm text-md-right">{{ __('Адрес:') }}</label>

                                <div class="col-md-4">
                                    <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') ?? $house->address }}" required autocomplete="address" autofocus>

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
                                        <input class="form-check-input" type="checkbox" value="internet" id="internet" name="facilities[]" @if($house->facility->internet) checked @endif>
                                        <label class="form-check-label" for="internet">
                                            Интернет
                                        </label>
                                    </div>
                                    <div class="form-check text-left">
                                        <input class="form-check-input" type="checkbox" value="wifi" id="wifi" name="facilities[]" @if($house->facility->wifi) checked @endif>
                                        <label class="form-check-label" for="wifi">
                                            Wi-Fi
                                        </label>
                                    </div>
                                    <div class="form-check text-left">
                                        <input class="form-check-input" type="checkbox" value="cable_tv" id="cable_tv" name="facilities[]" @if($house->facility->cable_tv) checked @endif>
                                        <label class="form-check-label" for="cable_tv">
                                            Кабельное ТВ
                                        </label>
                                    </div>
                                    <div class="form-check text-left">
                                        <input class="form-check-input" type="checkbox" value="conditioner" id="conditioner" name="facilities[]" @if($house->facility->conditioner) checked @endif>
                                        <label class="form-check-label" for="conditioner">
                                            Кондиционер
                                        </label>
                                    </div>
                                    <div class="form-check text-left">
                                        <input class="form-check-input" type="checkbox" value="washer" id="washer" name="facilities[]" @if($house->facility->washer) checked @endif>
                                        <label class="form-check-label" for="washer">
                                            Стиральная машина
                                        </label>
                                    </div>
                                </div>

                                <span class="col-md-2 col-form-label-sm text-md-right">{{ __('Ограничения:') }}</span>

                                <div class="col-md-4">
                                    <div class="form-check text-left">
                                        <input class="form-check-input" type="checkbox" value="animals" id="animals" name="restrictions[]" @if($house->restriction->animals) checked @endif>
                                        <label class="form-check-label" for="animals">
                                            Животные
                                        </label>
                                    </div>
                                    <div class="form-check text-left">
                                        <input class="form-check-input" type="checkbox" value="houseplants" id="houseplants" name="restrictions[]" @if($house->restriction->houseplants) checked @endif>
                                        <label class="form-check-label" for="houseplants">
                                            Комнатные растения
                                        </label>
                                    </div>
                                    <div class="form-check text-left">
                                        <input class="form-check-input" type="checkbox" value="no_smoke" id="no_smoke" name="restrictions[]" @if($house->restriction->no_smoke) checked @endif>
                                        <label class="form-check-label" for="no_smoke">
                                            Нельзя курить
                                        </label>
                                    </div>
                                    <div class="form-check text-left">
                                        <input class="form-check-input" type="checkbox" value="no_drink" id="no_drink" name="restrictions[]" @if($house->restriction->no_drink) checked @endif>
                                        <label class="form-check-label" for="no_drink">
                                            Нельзя пить
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="info" class="col-md-2 col-form-label-sm text-md-right">{{ __('Доп. инфо:') }}</label>

                                <div class="col-md-10">
                                    <textarea id="info" type="text" rows="5" class="form-control @error('info') is-invalid @enderror" name="info" autocomplete="info" autofocus>{{ old('info') ?? $house->info }}</textarea>

                                    @error('info')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Обновить</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

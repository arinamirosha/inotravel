@php
    $emptyErrors = !$errors->any();
@endphp
<div class="form-group row">
    <label for="name" class="col-md-2 col-form-label-sm text-md-right">{{ __('Accommodation name') }}</label>

    <div class="col-md-4">
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
               value="@if(isset($house)){{old('name',$house->name)}}@else{{old('name')}}@endif" autocomplete="name" autofocus>

        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <label for="city" class="col-md-2 col-form-label-sm text-md-right">{{ __('City') }}</label>

    <div class="col-md-4">
        <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city"
               value="@if(isset($house)){{old('city',$house->city)}}@else{{old('city')}}@endif" autocomplete="city" autofocus>

        @error('city')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label for="places" class="col-md-2 col-form-label-sm text-md-right">{{ __('Sleeping places') }}</label>

    <div class="col-md-4">
        <input id="places" type="number" class="form-control @error('places') is-invalid @enderror" name="places"
               value="@if(isset($house)){{old('places',$house->places)}}@else{{old('places')}}@endif" autocomplete="places" autofocus>

        @error('places')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <label for="address" class="col-md-2 col-form-label-sm text-md-right">{{ __('Address') }}</label>

    <div class="col-md-4">
        <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address"
               value="@if(isset($house)){{old('address',$house->address)}}@else{{old('address')}}@endif" autocomplete="address" autofocus>

        @error('address')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <span class="col-md-2 col-form-label-sm text-md-right">{{ __('Facilities') }}</span>

    <div class="col-md-4">
        @foreach($facilities as $fac)
            <div class="form-check text-left">
                <input class="form-check-input" type="checkbox" value="{{ $fac->id }}" id="{{ $fac->name }}" name="facilities[]"
                       @if(($emptyErrors && isset($house) && $house->facilities->contains($fac->id)) || (old('facilities') && in_array($fac->id, old('facilities')))) checked @endif>
                <label class="form-check-label" for="{{ $fac->name }}">
                    {{ $fac->value }}
                </label>
            </div>
        @endforeach
    </div>

    <span class="col-md-2 col-form-label-sm text-md-right">{{ __('Restrictions') }}</span>

    <div class="col-md-4">
        @foreach($restrictions as $res)
            <div class="form-check text-left">
                <input class="form-check-input" type="checkbox" value="{{ $res->id }}" id="{{ $res->name }}" name="restrictions[]"
                       @if(($emptyErrors && isset($house) && $house->restrictions->contains($res->id)) || (old('restrictions') && in_array($res->id, old('restrictions')))) checked @endif>
                <label class="form-check-label" for="{{ $res->name }}">
                    {{ $res->value }}
                </label>
            </div>
        @endforeach
    </div>
</div>

<div class="form-group row">
    <label for="info" class="col-md-2 col-form-label-sm text-md-right">{{ __('Add. info') }}</label>

    <div class="col-md-10">
        <textarea id="info" type="text" rows="5" class="form-control @error('info') is-invalid @enderror" name="info" autocomplete="info" autofocus>{{$house->info??old('info')}}</textarea>

        @error('info')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

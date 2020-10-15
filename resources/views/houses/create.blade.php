@extends('layouts.app')

@section('title')
    {{ __('Housing creation') }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row text-center">
            <div class="col-md-11">

                <div class="row">

                    <div class="col-md-3">

                        @php
                            $imgFailed = $errors->get('imgId') ? true : false;
                        @endphp

                        <form method="POST" enctype="multipart/form-data" id="form-file-ajax" action="{{ route('house.upload-image') }}">
                            @csrf
                            <img id="photo" src="{{url(old('imgId')&&!$imgFailed?\App\TemporaryImage::find(old('imgId'))->tempImage():'/images/noImage.svg')}}" alt="Image" width="400" class="w-100">
                            <input type="file" id="file" name="file" class="d-none">
                            <label for="file" class="col-form-label btn btn-outline-dark btn-block mt-3">{{ __('Select A New Photo') }}</label>
                            <div id="deletePhoto" class="btn btn-outline-secondary btn-block @if(old('imgId') && !$imgFailed) d-block @endif">{{ __('Remove Photo') }}</div>
                        </form>

                        <div id="message" class="text-danger font-weight-bold small mt-2"></div>

                        <div id="process" class="pt-3 text-center">
                            <img src="{{ url('/images/preloader.gif') }}" alt="Loading">
                        </div>

                    </div>

                    <div class="col-md-9">
                        <form method="POST" action="{{ route('house.store') }}" id="my-form">
                            @csrf

                            @include('inc.house_form_elements')

                            <input id="imgId" type="text" name="imgId" class="d-none @error('imgId') is-invalid @enderror" @if(!$imgFailed) value="{{old('imgId')}}" @endif>

                            @error('imgId')
                            <span class="invalid-feedback" role="alert" id="imgError">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary w-25">{{ __('Create') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

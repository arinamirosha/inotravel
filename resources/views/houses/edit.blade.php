@extends('layouts.app')

@section('title')
    Редактирование жилья &ndash; {{ $house->name }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row text-center">
            <div class="col-md-11">


                <div class="row">

                    <div class="col-md-3">

                        <form method="POST" enctype="multipart/form-data" id="form-file-ajax" action="{{ route('house.upload-image') }}">
                            @csrf
                            <img id="photo"
                                 src="@if(old('deleteImage')) {{url('/images/noImage.svg')}} @else {{url(old('imgId')?\App\TemporaryImage::find(old('imgId'))->tempImage():$house->houseImage())}} @endif"
                                 alt="Image" width="400" class="w-100">
                            <input type="file" id="file" name="file" class="d-none">
                            <label for="file" class="col-form-label btn btn-outline-dark btn-block mt-3">Выбрать фото</label>
                            <div id="deletePhoto" class="btn btn-outline-secondary btn-block @if(($house->image || old('imgId')) && !old('deleteImage')) d-block @endif">Удалить фото</div>
                        </form>

                        <div id="message" class="text-danger font-weight-bold small mt-2"></div>

                        <div id="process" class="pt-3 text-center">
                            <img src="{{ url('/images/preloader.gif') }}" alt="Loading">
                        </div>

                    </div>

                    <div class="col-md-9">
                        <form method="POST" action="{{ route('house.update', $house->id) }}" enctype="multipart/form-data">
                            @csrf

                            @include('inc.house_form_elements')

                            <input id="deleteImage" name="deleteImage" class="d-none" type="checkbox" @if(old('deleteImage')) checked @endif>
                            <input id="imgId" type="text" name="imgId" class="d-none" value="{{old('imgId')}}">

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Обновить</button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

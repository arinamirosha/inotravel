@extends('layouts.app')

@section('title')
    Создание жилья
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row text-center">
            <div class="col-md-11">

                <div class="row">

                    <div class="col-md-3">

                        <form method="POST" enctype="multipart/form-data" id="form-file-ajax" action="{{ route('house.upload-image') }}">
                            @csrf
                            <img id="photo" src="{{ url('/images/noImage.svg') }}" alt="Image" width="400" class="w-100">
                            <input type="file" id="file" name="file" class="d-none" required><br/>
                            <label for="file" class="col-form-label btn btn-outline-dark btn-block mt-3">Выбрать фото</label>
                            <div id="deletePhoto" class="btn btn-outline-secondary btn-block">Удалить фото</div>
                        </form>

                        <div id="process" class="pt-3 text-center">
                            <img src="{{ url('/images/preloader.gif') }}" alt="Loading">
                        </div>

                    </div>

                    <div class="col-md-9">
                        <form method="POST" action="{{ route('house.store') }}" id="my-form">
                            @csrf

                            @include('inc.house_form_elements')

                            <input id="imgId" type="text" class="d-none" name="imgId">

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Создать профиль</button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

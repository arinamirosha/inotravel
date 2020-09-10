@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row text-center">
            <div class="col-md-11">
                <form method="POST" action="{{ route('house.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        <div class="col-md-3">
                            <div id="mydiv" style="word-wrap:break-word">Нет фото</div>
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

                            @include('inc.house_form_elements')

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Создать профиль</button>
                                </div>
                            </div>

                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row text-center">
            <div class="col-md-11">
                <form method="POST" action="{{ route('house.update', $house->id) }}" enctype="multipart/form-data">
                    @csrf

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

                            @include('inc.house_form_elements')

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

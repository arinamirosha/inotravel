@extends('layouts.app')

@section('title')
    {{ __('Editing housing') }} &ndash; {{ $house->name }}
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
                            <img id="photo"
                                 src="@if(old('deleteImage') || $imgFailed) {{ url('/images/noImage.svg') }} @else {{ url(old('imgId') ? \App\TemporaryImage::find(old('imgId'))->tempImage() : $house->houseImage()) }} @endif"
                                 alt="Image" width="400" class="w-100">
                            <input type="file" id="file" name="file" class="d-none">
                            <label for="file" class="col-form-label btn btn-outline-dark btn-block mt-3">{{ __('Select A New Photo') }}</label>
                            <div id="deletePhoto" class="btn btn-outline-secondary btn-block @if(($house->image || old('imgId')) && !(old('deleteImage') || $imgFailed)) d-block @endif">{{ __('Remove Photo') }}</div>
                        </form>

                        <div id="message" class="text-danger font-weight-bold small mt-2"></div>

                        <div id="process" class="pt-3 text-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-9">
                        <form method="POST" action="{{ route('house.update', $house->id) }}" enctype="multipart/form-data">
                            @csrf

                            @include('inc.house_form_elements')

                            <input id="deleteImage" name="deleteImage" class="d-none" type="checkbox" @if(old('deleteImage') || $imgFailed) checked @endif>
                            <input id="imgId" type="text" name="imgId" class="d-none @error('imgId') is-invalid @enderror" @if(!$imgFailed) value="{{ old('imgId') }}" @endif>

                            @error('imgId')
                            <span class="invalid-feedback" role="alert" id="imgError">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            <span class="border text-danger border-0" id="imagesError">
                                <strong>
                                    @if($errors->has('images.*'))
                                        {{ $errors->first('images.*') }}
                                    @endif
                                    @if($errors->has('oldimages.*'))
                                        {{ $errors->first('oldimages.*') }}
                                    @endif
                                </strong>
                            </span>

                            @php
                                $emptyErrors = !$errors->any();
                            @endphp

                            <div id="input-gallery" class="d-none">
                                @if(!$emptyErrors && !$errors->has('images.*') && old('images'))
                                    @foreach(old('images') as $img)
                                        <input type="text" name="images[]" id="input-gallery-img-{{$img}}" value="{{$img}}">
                                    @endforeach
                                @endif

                                @if (!$emptyErrors && !$errors->has('oldimages.*') && old('oldimages'))
                                    @foreach(old('oldimages') as $img)
                                        <input type="text" name="oldimages[]" id="input-gallery-img-{{$img}}" value="{{$img}}">
                                    @endforeach
                                @endif

                                @if ($emptyErrors && $house->gallery()->exists())
                                    @foreach($house->gallery as $img)
                                        <input type="text" name="oldimages[]" id="input-gallery-img-{{$img->id}}" value="{{$img->id}}">
                                    @endforeach
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary w-25">{{ __('Update') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>

                <div class="row mt-2">
                    <div class="col-md-3">
                        <form method="POST" enctype="multipart/form-data" id="form-more-images-ajax" action="{{ route('house.upload-images') }}">
                            @csrf
                            <input type="file" id="images" name="images[]" class="d-none" multiple accept="image/*">
                            <label for="images" class="col-form-label btn btn-outline-dark btn-block mt-3">{{ __('Add more images') }}</label>
                            <div id="message-images" class="text-danger font-weight-bold small mt-2"></div>
                            <div id="process" class="pt-3 text-center">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">{{ __('Loading...') }}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-9">
                        <div id="gallery" class="row justify-content-start">
                            @if(!$emptyErrors && !$errors->has('images.*') && old('images'))
                                @foreach(old('images') as $img)
                                    <div class="col-md-2" id="gallery-img-{{$img}}">
                                        <div><img width="100" height="100" src="{{ url(\App\TemporaryImage::find($img)->tempImage()) }}"></div>
                                        <div><a href="#" id="{{$img}}" class="after-validation-error"><i class="far fa-trash-alt"></i></a></div>
                                    </div>
                                @endforeach
                            @endif

                            @if(!$emptyErrors && !$errors->has('oldimages.*') && old('oldimages'))
                                @foreach(old('oldimages') as $img)
                                    <div class="col-md-2" id="gallery-img-{{$img}}">
                                        <div><img width="100" height="100" src="{{ url(\App\Gallery::find($img)->galleryImage()) }}"></div>
                                        <div><a href="#" id="{{$img}}" class="delete-old-images"><i class="far fa-trash-alt"></i></a></div>
                                    </div>
                                @endforeach
                            @endif

                            @if($emptyErrors && $house->gallery()->exists())
                                @foreach($house->gallery as $img)
                                    <div class="col-md-2" id="gallery-img-{{$img->id}}">
                                        <div><img width="100" height="100" src="{{ url($img->galleryImage()) }}"></div>
                                        <div><a href="#" id="{{$img->id}}" class="delete-old-images"><i class="far fa-trash-alt"></i></a></div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

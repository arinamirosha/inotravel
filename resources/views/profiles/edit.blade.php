@extends('layouts.app')

@section('title')
    {{ $user->name }} {{ $user->surname }} &ndash; {{__('Edit')}}
@endsection

@section('content')
<div class="container light-bg">
    <div class="row justify-content-center">
        <div class="col-md-10 text-center">

            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="row">

                        <div class="col-2 col-md-3 col-lg-2">
                            <a class="navbar-item" href="{{ route('profile.show', $user->id) }}" title="{{__('Back to profile')}}">
                                <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-chevron-left" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                                </svg>
                            </a>
                        </div>

                        <div class="col-8 col-md-9 col-lg-10 h3 pb-2">{{ __('Edit profile') }}</div>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $user->name }}" autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Surname') }}</label>

                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') ?? $user->surname }}" autocomplete="surname" autofocus>

                                @error('surname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?? $user->email }}" autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 h3">
                                <button type="submit" class="btn btn-primary w-25">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-12 h3 pb-2 pt-4 pt-md-0">{{ __('Change password') }}</div>
                    </div>

                    <form method="POST" action="{{ route('profile.update_password') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="passwordOld" class="col-md-4 col-form-label text-md-right">{{ __('Old Password') }}</label>

                            <div class="col-md-6">
                                <input id="passwordOld" type="password" class="form-control @error('passwordOld') is-invalid @enderror" name="passwordOld" autocomplete="passwordOld">

                                @error('passwordOld')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 h3">
                                <button type="submit" class="btn btn-primary w-25">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if(session('message'))
                <div class="row justify-content-center pt-5">
                    <div class="alert alert-info col-md-5 text-center">
                        {{ session('message') }}
                    </div>
                </div>
            @endif

            <div class="row mt-2">
                <div class="col-md-3 offset-md-4">
                    @php
                        $imgFailed = $errors->get('imgId') ? true : false;
                    @endphp

                    <form method="POST" enctype="multipart/form-data" id="form-file-ajax" action="{{ route('profile.upload-avatar') }}">
                        @csrf
                        <img id="photo" src="{{url($user->avatarImg())}}" alt="Image" class="w-100">
                        <input type="file" id="avatar" name="file" class="d-none">
                        <label for="avatar" class="col-form-label btn btn-outline-dark btn-block mt-3">{{ __('Select A New Photo') }}</label>
                        <div id="deleteAvatar" class="btn btn-outline-secondary btn-block @if(! $user->avatar) invisible @endif">{{ __('Remove Photo') }}</div>
                    </form>

                    <div id="message" class="text-danger font-weight-bold small mt-2"></div>

                    <div id="process" class="pt-3 text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

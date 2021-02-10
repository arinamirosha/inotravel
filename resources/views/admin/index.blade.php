@extends('layouts.app')

@section('title')
    {{ __('Users') }}
@endsection

@section('content')
    <div class="container light-bg">

        @if($notifications->isNotEmpty())
            <new-user-notifications
                route="{{ route('admin.mark-as-read') }}"
                showroute="{{ route('profile.show', 'userid') }}"
                notifications="{{ json_encode($notifications) }}"
                textbutton="{{ __('Mark As Read') }}"
                textbuttonall="{{ __('Mark All As Read') }}"
            ></new-user-notifications>
        @endif

        <form action="{{ route('admin.index') }}" method="get" id="search-user-form">
            @csrf
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="input-group mb-3">
                        <input name="searchUserData" class="form-control" placeholder="{{ __("Enter name or surname") }}" aria-label="User's data" >
                        <button class="btn btn-outline-secondary" type="submit">{{ __('Search') }}</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="select-user" name="selectUser" aria-label="select">
                        <option value="{{ \App\User::ALL }}">{{ __('All') }}</option>
                        <option value="{{ \App\User::ADMIN }}">{{ __('Administrator') }}</option>
                        <option value="{{ \App\User::SUPER_ADMIN }}">{{ __('Super Admin') }}</option>
                        <option value="{{ \App\User::NO_ADMIN }}">{{ __('No Admin') }}</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="row justify-content-center">
            <strong class="text-danger" id="errorSearchUserData"></strong>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-10" id="result_wrap">

                @include('admin.users')

            </div>
        </div>
    </div>
@endsection

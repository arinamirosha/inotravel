@extends('layouts.app')

@section('title')
    {{ __('Users') }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row justify-content-center">
            <div class="col-md-10" id="result_wrap">

                @include('admin.users')

            </div>
        </div>
    </div>
@endsection

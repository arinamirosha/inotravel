@extends('layouts.app')

@section('title')
    {{ __('History') }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row text-center">
            <div class="col-md-12">

                @include('inc.history_result')

            </div>
        </div>
    </div>
@endsection

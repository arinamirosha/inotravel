@extends('layouts.app')

@section('title')
{{ $user->name }} {{ $user->surname }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row h4">
            <div class="col-3 col-md-3">
                <img src="{{ url($user->avatarImg()) }}" alt="" class="w-100 rounded mb-3">
            </div>
            <div class="col-4 col-md-2 font-weight-bold pt-md-4 pl-md-4">
                <div>{{__('Name')}}</div>
                <div>{{__('Surname')}}</div>
                <div>{{__('E-Mail')}}</div>
            </div>
            <div class="col-5 col-md-3 col-lg-4 pt-md-4">
                <div>{{$user->name}}</div>
                <div>{{$user->surname}}</div>
                <div>{{$user->email}}</div>
            </div>
            @can('update', $user)
                <div class="col-md-3 col-lg-2 text-right">
                    <a class="btn btn-outline-primary btn-block" href="{{ route('profile.edit') }}">{{ __('Edit') }}</a>
                </div>
            @endcan
        </div>

        @if($user->houses()->exists())
        <div class="row pb-2">
            <div class="col-2 col-md-1 mt-1 h5">
                {{ __('Housing') }}
            </div>
            <div class="col-9 col-md-10 mt-3 border-top border-dark">
            </div>
            <div class="col-1 h4">
                {{$user->houses()->count()}}
            </div>
        </div>
        @endif

        <div class="row mt-3 justify-content-start">
            @forelse($user->houses as $house)
                <div class="mr-3 mb-4 col-5 col-md-3 col-lg-2">
                    <div><a href="{{ route('house.show', $house->id) }}"><img src="{{ url($house->houseImage()) }}" alt="" class="w-100 rounded"></a></div>
                    <div><a href="{{ route('house.show', $house->id) }}">{{ $house->name }}</a></div>
                    <div>{{ $house->city }}</div>
                </div>
            @empty
                <div class="col-md-12 p-3 h5 text-center">
                    {{ __('Does not provide accommodation') }}
                </div>
            @endforelse
        </div>

    </div>
@endsection

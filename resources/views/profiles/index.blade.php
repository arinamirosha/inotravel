@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">

                @foreach($users as $user)

                    <div class="row mb-2">
                        <div class="col-4 font-weight-bold h5">
                            {{ $user->name }} {{ $user->surname }}
                        </div>
                        <div class="col-4 h5">
                            {{ $user->email }}
                        </div>
                        <div class="col-4 h5">
                            @if($user->admin)
                                Администратор
                            @else
                                <a href="{{ route('profiles.make_admin', $user->id) }}" class="btn btn-outline-secondary btn-sm">Сделать админом</a>
                            @endif
                        </div>
                    </div>


                @endforeach

            </div>
        </div>
    </div>
@endsection

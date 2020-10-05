@extends('layouts.email')

@section('content')

    <div class="row text-center mt-4">
        <div class="col-12 h3">
            <div>
                Одобренная заявка
            </div>
            <div>
                с <b>{{$arrival}}</b> по <b>{{$departure}}</b>
            </div>
            <div class="m-3">
                <div class="font-weight-bold h2">
                    удалена
                </div>
                <div>
                    в связи с тем, что хозяин удалил свое жилье
                </div>
            </div>
            <div>
                Название: <b>{{$name}}</b>
            </div>
            <div>
                Город: <b>{{$city}}</b>
            </div>
        </div>
    </div>

@endsection

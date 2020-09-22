@extends('layouts.email')

@section('content')

    <div class="row">
        <div class="col-8 h3">
            <div>Одобренная заявка с <b>{{$arrival}}</b> по <b>{{$departure}}</b> удалена в связи с тем, что хозяин удалил свое жилье</div>
            <div>Название: <b>{{$name}}</b></div>
            <div>Город: <b>{{$city}}</b></div>
        </div>
    </div>

@endsection

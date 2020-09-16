@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row text-center">
            <div class="col-md-5">
                <div style="height: 500px; overflow: hidden">
                    <img src="{{ $house->houseImage() }}" alt="" class="w-100">
                </div>
            </div>
            <div class="col-md-6 text-left h5 ml-3">
                <div class="row">
                    <div class="col-8">
                        <div>{{ $house->name }}</div>
                        <div>{{ $house->city }}, {{ $house->address }}</div>
                        <div>{{ $house->user->name }} {{ $house->user->surname }}</div>
                    </div>
                    <div class="col-4">
                        @can('update', $house->user)
                            <a href="{{ route('house.edit', $house->id) }}" class="btn btn-outline-dark btn-block">Редактировать</a>
                            <form action="{{ route('house.destroy', $house->id) }}" method="post" class="pt-2">
                                @csrf
                                <button class="btn btn-outline-secondary btn-block" onclick="return confirm('Вы уверены, что хотите удалить?')">Удалить</button>
                            </form>
                        @else

                            @if ($isBooked)
                                <div class="mb-1 btn btn-outline-secondary btn-block disabled">Заявка отправлена!</div>
                            @else
                                @if($arrival)
                                    @if ($enoughPlaces)
                                        <div>
                                            @if ($isFree)
                                                <div class="alert alert-info text-center">Свободно
                                                    {{--                                                    .. мест--}}
                                                </div>
                                                <div class="mb-1">
                                                    <form action="{{ route('booking.store') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $house->id }}" name="houseId">
                                                        <input type="hidden" value="{{ $arrival }}" name="arrival">
                                                        <input type="hidden" value="{{ $departure }}" name="departure">
                                                        <input type="hidden" value="{{ $people }}" name="people">
                                                        <button class="btn btn-outline-secondary btn-block">Забронировать</button>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="alert alert-info">Даты заняты
{{--                                                    / нет мест--}}
                                                </div>
                                            @endif
                                        </div>
                                        <div>c {{ $arrival }}</div>
                                        <div>по {{ $departure }}</div>
                                        <div>людей: {{ $people }}</div>
                                    @else
                                        <div class="alert alert-info">Недостаточно спальных мест</div>
                                    @endif
                                @else
                                    <div class="alert alert-info">Используйте поиск, чтобы задать даты прибытия и отъезда</div>
                                @endif
                            @endif

                        @endcan
                    </div>
                </div>
                <div class="pt-3">
                    <strong class="pr-2">Спальных мест </strong>{{ $house->places }}
                </div>
                <div class="pt-3">
                    @if($house->facilities()->exists())
                        <strong>Удобства</strong>
                        <div class="row text-center h6 pt-3">

                            @foreach($house->facilities as $facility)
                                <div class="col-2">
                                    <div><img src="/images/{{ $facility->name }}.png" alt="" class="w-50"></div>
                                    <div>{{ $facility->value }}</div>
                                </div>
                            @endforeach

                        </div>
                    @endif
                </div>
                <div class="pt-3">
                    @if($house->restrictions()->exists())
                        <strong>Ограничения</strong>
                        <div class="row text-center h6 pt-3">

                            @foreach($house->restrictions as $restriction)
                                <div class="col-2">
                                    <div><img src="/images/{{ $restriction->name }}.png" alt="" class="w-50"></div>
                                    <div>{{ $restriction->value }}</div>
                                </div>
                            @endforeach

                        </div>
                    @endif
                </div>
                @if($house->info)
                    <div class="pt-3">
                        <strong>Дополнительная информация</strong>
                        <div class="pt-1">
                            {{ $house->info }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

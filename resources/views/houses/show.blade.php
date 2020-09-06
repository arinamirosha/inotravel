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
                                @method('delete')
                                <button class="btn btn-outline-secondary btn-block" onclick="return confirm('Вы уверены, что хотите удалить?')">Удалить</button>
                            </form>
                        @else
                            @if(session('arrival') && session('departure') && session('people'))

                                @if ($isBooked)
                                    <div class="mb-1 btn btn-outline-secondary btn-block disabled">Заявка отправлена!</div>
                                @else

                                    @if ($house->places < session('people'))
                                        <div class="alert alert-info">Недостаточно спальных мест</div>
                                    @else

                                        <div>
                                            @if ($isFree)
                                                <div class="alert alert-info">Свободно .. мест</div>
                                                <div class="mb-1">
                                                    <form action="{{ route('booking.store', $house->id) }}" method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $house->id }}" name="house_id">
                                                        <button class="btn btn-outline-secondary btn-block">Забронировать</button>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="alert alert-info">Даты заняты / нет мест</div>
                                            @endif
                                        </div>
                                        <div>людей: {{ session('people') }}</div>
                                        <div>c {{ session('arrival') }}</div>
                                        <div>по {{ session('departure') }}</div>

                                    @endif

                                @endif

                            @else
                                <div class="alert alert-info">Используйте поиск, чтобы задать даты прибытия и отъезда</div>
                            @endif
                        @endcan
                    </div>
                </div>
                <div class="pt-3">
                    <strong class="pr-2">Спальных мест </strong>{{ $house->places }}
                </div>
                <div class="pt-3">
                    @if($house->facility->facilitiesExist())
                        <strong>Удобства</strong>
                        <div class="row text-center h6 pt-3">
                            @if($house->facility->internet)
                                <div class="col-2">
                                    <div><img src="/storage/icons/internet.png" alt="" class="w-50"></div>
                                    <div>Интернет</div>
                                </div>
                            @endif
                            @if($house->facility->wifi)
                                <div class="col-2">
                                    <div><img src="/storage/icons/wifi.png" alt="" class="w-50"></div>
                                    <div>Wi-Fi</div>
                                </div>
                            @endif
                            @if($house->facility->cable_tv)
                                <div class="col-2">
                                    <div><img src="/storage/icons/tv.png" alt="" class="w-50"></div>
                                    <div>Кабельное ТВ</div>
                                </div>
                            @endif
                            @if($house->facility->conditioner)
                                <div class="col-2">
                                    <div><img src="/storage/icons/conditioner.png" alt="" class="w-50"></div>
                                    <div>Кондиционер</div>
                                </div>
                            @endif
                            @if($house->facility->washer)
                                <div class="col-2">
                                    <div><img src="/storage/icons/washer.png" alt="" class="w-50"></div>
                                    <div>Стиральная машина</div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="pt-3">
                    @if($house->restriction->restrictionsExist())
                        <strong>Ограничения</strong>
                        <div class="row text-center h6 pt-3">
                            @if($house->restriction->animals)
                                <div class="col-2">
                                    <div><img src="/storage/icons/animal.png" alt="" class="w-50"></div>
                                    <div>Животные</div>
                                </div>
                            @endif
                            @if($house->restriction->houseplants)
                                <div class="col-2">
                                    <div><img src="/storage/icons/plant.png" alt="" class="w-50"></div>
                                    <div>Комнатные растения</div>
                                </div>
                            @endif
                            @if($house->restriction->no_smoke)
                                <div class="col-2">
                                    <div><img src="/storage/icons/nosmoke.png" alt="" class="w-50"></div>
                                    <div>Нельзя курить</div>
                                </div>
                            @endif
                            @if($house->restriction->no_drink)
                                <div class="col-2">
                                    <div><img src="/storage/icons/nodrink.png" alt="" class="w-50"></div>
                                    <div>Нельзя пить</div>
                                </div>
                            @endif
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

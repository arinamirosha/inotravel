@extends('layouts.app')

@section('title')
    {{ __('Viewing housing') }} &ndash; {{ $house->name }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row text-center">
            <div class="col-md-5">
                <img src="{{ url($house->houseImage()) }}" alt="" class="w-100 rounded mb-3">
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
                            <a href="{{ route('house.edit', $house->id) }}" class="btn btn-outline-primary btn-block">{{ __('Edit') }}</a>
                            <form action="{{ route('house.destroy', $house->id) }}" method="post" class="pt-2">
                                @csrf
                                <button class="btn btn-outline-danger btn-block" onclick="return confirm('{{ __('Are you sure you want to delete?') }}')">{{ __('Delete') }}</button>
                            </form>
                        @else

                            @if ($isBooked)
                                <div class="mb-1 btn btn-outline-secondary btn-block disabled">{{ __('Application has been sent!') }}</div>
                            @else
                                @if($arrival)
                                    @if ($enoughPlaces)
                                        <div>
                                            @if ($isFree)
                                                <div class="alert alert-info text-center">{{ __('Free') }}
                                                </div>
                                                <div class="mb-1">
                                                    <form action="{{ route('booking.store') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $house->id }}" name="houseId">
                                                        <input type="hidden" value="{{ $arrival }}" name="arrival">
                                                        <input type="hidden" value="{{ $departure }}" name="departure">
                                                        <input type="hidden" value="{{ $people }}" name="people">
                                                        <button class="btn btn-outline-secondary btn-block">{{ __('Book now') }}</button>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="alert alert-info">{{ __('Dates are busy') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>{{ __('from') }} {{ $arrival }}</div>
                                        <div>{{ __('to') }} {{ $departure }}</div>
                                        <div>{{ __('People') }}: {{ $people }}</div>
                                    @else
                                        <div class="alert alert-info">{{ __('Not enough sleeping places') }}</div>
                                    @endif
                                @else
                                    <div class="alert alert-info">{{ __('Use search to set arrival and departure dates') }}</div>
                                @endif
                            @endif

                        @endcan
                    </div>
                </div>
                <div class="pt-3">
                    <strong class="pr-2">{{ __('Sleeping places') }} </strong>{{ $house->places }}
                </div>
                <div class="pt-3">
                    @if($house->facilities()->exists())
                        <strong>{{ __('Facilities') }}</strong>
                        <div class="row text-center h6 pt-3">

                            @foreach($house->facilities as $facility)
                                <div class="col-2">
                                    <div><img src="{{ url($facility->image()) }}" alt="" class="w-50"></div>
                                    <div>{{ __($facility->value) }}</div>
                                </div>
                            @endforeach

                        </div>
                    @endif
                </div>
                <div class="pt-3">
                    @if($house->restrictions()->exists())
                        <strong>{{ __('Restrictions') }}</strong>
                        <div class="row text-center h6 pt-3">

                            @foreach($house->restrictions as $restriction)
                                <div class="col-2">
                                    <div><img src="{{ url($restriction->image()) }}" alt="" class="w-50"></div>
                                    <div>{{ __($restriction->value) }}</div>
                                </div>
                            @endforeach

                        </div>
                    @endif
                </div>
                @isset($house->info)
                    <div class="pt-3">
                        <strong>{{ __('Additional information') }}</strong>
                        <div class="pt-1">
                            {{ $house->info }}
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </div>
@endsection

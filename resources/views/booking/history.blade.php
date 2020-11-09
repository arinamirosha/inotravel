@extends('layouts.app')

@section('title')
    {{ __('History') }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row text-center">
            <div class="col-md-12">


                @forelse($histories as $history)
                    <div class="row p-1 h6 @if ($history->booking->house->user->id == Auth::id()) my-house @endif">

                        <div class="col-md-1">
                            <a href="{{ route('house.show', $history->booking->house->id) }}">
                                <img src="{{ url($history->booking->house->houseImage()) }}" alt="" class="w-100 rounded">
                            </a>
                        </div>

                        <div class="col-md-2 text-left">
                            <div>
                                <a href="{{ route('house.show', $history->booking->house->id) }}">{{ $history->booking->house->name }}</a>
                            </div>
                            <div>
                                {{ $history->booking->house->city }}
                            </div>
                        </div>

                        <div class="col-md-2 text-left">
                            <div>
                                {{ Carbon\Carbon::parse($history->booking->arrival)->format('d/m/y') }} - {{ Carbon\Carbon::parse($history->booking->departure)->format('d/m/y') }}
                            </div>
                            <div>
                                People: {{ $history->booking->people }}
                            </div>
                        </div>

                        <div class="col-md-5">
                            @switch($history->type)
                                @case(\App\BookingHistory::TYPE_SENT)
                                    <div class="text-secondary">
                                        {{ __('Under consideration by') }} {{ $history->booking->house->user->name }} {{ $history->booking->house->user->surname }}
                                    </div>{{ __('Outgoing') }}
                                @break
                                @case(\App\BookingHistory::TYPE_RECEIVED)
                                    <div class="text-secondary">
                                        {{ __('Received from') }} {{ $history->booking->user->name }} {{ $history->booking->user->surname }}
                                    </div>{{ __('Incoming') }}
                                @break
                                @case(\App\BookingHistory::TYPE_ACCEPTED)
                                <div class="text-success">
                                    {{ __('Accepted by me to') }} {{ $history->booking->user->name }} {{ $history->booking->user->surname }}
                                </div>{{ __('Outgoing') }}
                                @break
                                @case(\App\BookingHistory::TYPE_ACCEPTED_ANSWER)
                                <div class="text-success">
                                    {{ __('Accepted by') }} {{ $history->booking->house->user->name }} {{ $history->booking->house->user->surname }}
                                </div>{{ __('Incoming') }}
                                @break
                                @case(\App\BookingHistory::TYPE_REJECTED)
                                <div class="text-danger">
                                    {{ __('Rejected by me to') }} {{ $history->booking->user->name }} {{ $history->booking->user->surname }}
                                </div>{{ __('Outgoing') }}
                                @break
                                @case(\App\BookingHistory::TYPE_REJECTED_ANSWER)
                                <div class="text-danger">
                                    {{ __('Rejected by') }} {{ $history->booking->house->user->name }} {{ $history->booking->house->user->surname }}
                                </div>{{ __('Incoming') }}
                                @break
                                @case(\App\BookingHistory::TYPE_CANCELLED)
                                <div class="text-danger">
                                    {{ __('Cancelled by me to') }} {{ $history->booking->house->user->name }} {{ $history->booking->house->user->surname }}
                                </div>{{ __('Outgoing') }}
                                @break
                                @case(\App\BookingHistory::TYPE_CANCELLED_INFO)
                                <div class="text-danger">
                                    {{ __('Cancelled by') }} {{ $history->booking->user->name }} {{ $history->booking->user->surname }}
                                </div>{{ __('Incoming') }}
                                @break
                                @case(\App\BookingHistory::TYPE_SENT_BACK)
                                <div class="text-secondary">
                                    {{ __('Sent back by me for ') }} {{ $history->booking->house->user->name }} {{ $history->booking->house->user->surname }}
                                </div>{{ __('Outgoing') }}
                                @break
                                @case(\App\BookingHistory::TYPE_SENT_BACK_INFO)
                                <div class="text-secondary">
                                    {{ __('Sent back by') }} {{ $history->booking->user->name }} {{ $history->booking->user->surname }}
                                </div>{{ __('Incoming') }}
                                @break

                                @case(\App\BookingHistory::TYPE_DELETED)
                                <div class="text-danger">
                                    {{ __('Deleted by me') }}
                                </div>{{ __('Outgoing') }}
                                @break

                            @endswitch
                        </div>

                        <div class="col-md-2">{{ Carbon\Carbon::parse($history->created_at)->format('d/m/y h:m:s')  }}</div>

                    </div>
                @empty
                    <div class="row justify-content-center">
                        <div class="col-md-12 p-5 h2">
                            {{ __('History is empty') }}
                        </div>
                    </div>
                @endforelse

                <div class="row offset-1">
                    <div class="col-6">
                        {{$histories->links()}}
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

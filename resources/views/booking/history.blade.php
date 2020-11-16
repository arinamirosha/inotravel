@extends('layouts.app')

@section('title')
    {{ __('History') }}
@endsection

@section('content')
    <div class="container light-bg">
        <div class="row text-center">
            <div class="col-md-12">

                @forelse($histories as $history)

                    @if ($loop->first)
                        <div class="row p-2 h6 font-weight-bold rounded bg-title">
                            <div class="col-md-4">{{ __('Accommodation/application') }}</div>
                            <div class="col-md-2">{{ __('Who') }}</div>
                            <div class="col-md-1">{{ __('Action') }}</div>
                            <div class="col-md-2">{{ __('To whom') }}</div>
                            <div class="col-md-1">{{ __('Type') }}</div>
                            <div class="col-md-2">{{ __('Date') }}</div>
                        </div>
                    @endif

                    <div class="row p-1 h6 rounded @if ($history->booking->house->user_id == Auth::id()) my-house @else not-my-house @endif">

                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-2 p-0">
                                    <a href="{{ route('house.show', $history->booking->house_id) }}">
                                        <img src="{{ url($history->booking->house->houseImage()) }}" alt="" class="w-100 rounded">
                                    </a>
                                </div>
                                <div class="col-10 text-left">
                                    <div>
                                        <a href="{{ route('house.show', $history->booking->house->id) }}">{{ $history->booking->house->name }}</a>
                                    </div>
                                    <div>
                                        {{ $history->booking->house->city }}
                                    </div>
                                    <div>
                                        {{ Carbon\Carbon::parse($history->booking->arrival)->format('d/m/y') }} - {{ Carbon\Carbon::parse($history->booking->departure)->format('d/m/y') }},
                                        {{ $history->booking->people }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            @switch($history->type)
                                @case(\App\BookingHistory::TYPE_SENT)
                                @case(\App\BookingHistory::TYPE_ACCEPTED)
                                @case(\App\BookingHistory::TYPE_REJECTED)
                                @case(\App\BookingHistory::TYPE_CANCELLED)
                                @case(\App\BookingHistory::TYPE_SENT_BACK)
                                @case(\App\BookingHistory::TYPE_DELETED)
                                    &ndash;
                                @break
                                @case(\App\BookingHistory::TYPE_RECEIVED)
                                @case(\App\BookingHistory::TYPE_CANCELLED_INFO)
                                @case(\App\BookingHistory::TYPE_SENT_BACK_INFO)
                                {{ $history->booking->user->name }} {{ $history->booking->user->surname }}
                                @break
                                @case(\App\BookingHistory::TYPE_ACCEPTED_ANSWER)
                                @case(\App\BookingHistory::TYPE_REJECTED_ANSWER)
                                @case(\App\BookingHistory::TYPE_DELETED_INFO)
                                {{ $history->booking->house->user->name }} {{ $history->booking->house->user->surname }}
                                @break
                            @endswitch
                        </div>

                        <div class="col-md-1">
                            @switch($history->type)
                                @case(\App\BookingHistory::TYPE_SENT)
                                @case(\App\BookingHistory::TYPE_RECEIVED)
                                <div class="text-secondary">{{ __('Sent') }}</div>
                                @break
                                @case(\App\BookingHistory::TYPE_ACCEPTED)
                                @case(\App\BookingHistory::TYPE_ACCEPTED_ANSWER)
                                <div class="text-success">{{ __('Accepted') }}</div>
                                @break
                                @case(\App\BookingHistory::TYPE_REJECTED)
                                @case(\App\BookingHistory::TYPE_REJECTED_ANSWER)
                                <div class="text-danger">{{ __('Declined') }}</div>
                                @break
                                @case(\App\BookingHistory::TYPE_CANCELLED)
                                @case(\App\BookingHistory::TYPE_CANCELLED_INFO)
                                <div class="text-danger">{{ __('Cancelled') }}</div>
                                @break
                                @case(\App\BookingHistory::TYPE_SENT_BACK)
                                @case(\App\BookingHistory::TYPE_SENT_BACK_INFO)
                                <div class="text-secondary">{{ __('Sent back') }}</div>
                                @break
                                @case(\App\BookingHistory::TYPE_DELETED)
                                @case(\App\BookingHistory::TYPE_DELETED_INFO)
                                <div class="text-danger">{{ __('Deleted') }}</div>
                                @break
                            @endswitch
                        </div>

                        <div class="col-md-2">
                            @switch($history->type)
                                @case(\App\BookingHistory::TYPE_SENT)
                                    {{ $history->booking->house->user->name }} {{ $history->booking->house->user->surname }}
                                @break
                                @case(\App\BookingHistory::TYPE_RECEIVED)
                                @case(\App\BookingHistory::TYPE_ACCEPTED_ANSWER)
                                @case(\App\BookingHistory::TYPE_REJECTED_ANSWER)
                                @case(\App\BookingHistory::TYPE_CANCELLED_INFO)
                                @case(\App\BookingHistory::TYPE_SENT_BACK_INFO)
                                @case(\App\BookingHistory::TYPE_DELETED_INFO)
                                    &ndash;
                                @break
                                @case(\App\BookingHistory::TYPE_ACCEPTED)
                                @case(\App\BookingHistory::TYPE_REJECTED)
                                @case(\App\BookingHistory::TYPE_DELETED)
                                    {{ $history->booking->user->name }} {{ $history->booking->user->surname }}
                                @break
                                @case(\App\BookingHistory::TYPE_CANCELLED)
                                @case(\App\BookingHistory::TYPE_SENT_BACK)
                                    {{ $history->booking->house->user->name }} {{ $history->booking->house->user->surname }}
                                @break
                            @endswitch
                        </div>

                        <div class="col-md-1">
                            @if(in_array($history->type, [
                                \App\BookingHistory::TYPE_SENT,
                                \App\BookingHistory::TYPE_ACCEPTED,
                                \App\BookingHistory::TYPE_REJECTED,
                                \App\BookingHistory::TYPE_CANCELLED,
                                \App\BookingHistory::TYPE_CANCELLED,
                                \App\BookingHistory::TYPE_SENT_BACK,
                                \App\BookingHistory::TYPE_DELETED,
                                ]))
                                {{ __('Outgoing') }}
                            @else
                                {{ __('Incoming') }}
                            @endif
                        </div>

                        <div class="col-md-2">
                            {{ Carbon\Carbon::parse($history->created_at)->format('d/m/y h:m:s')  }}
                        </div>

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

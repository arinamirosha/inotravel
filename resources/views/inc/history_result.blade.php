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
            @include('inc.columns.sender')
        </div>

        <div class="col-md-1">
            @include('inc.columns.status_of_booking')
        </div>

        <div class="col-md-2">
            @include('inc.columns.recipient')
        </div>

        <div class="col-md-1">
            @include('inc.columns.incoming_or_outgoing')
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

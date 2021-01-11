<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="6000">
    <div class="toast-header">
        <strong class="mr-auto text-truncate">{{ $data['houseName'] }}</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true"></span>
        </button>
    </div>
    <a href="{{ route('house.index') }}" class="text-decoration-none text-secondary ">
        <div class="toast-body">
            {{ __('Application from') }} {{ $data['userName'] }} {{ $data['userSurname'] }} <br>
            {{ __('from') }} {{ $data['arrival'] }} {{ __('from') }} {{ $data['departure'] }}
        </div>
    </a>
</div>

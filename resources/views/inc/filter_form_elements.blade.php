<div class="mb-4">
    <div class="text-center font-weight-bold">{{ __('Accommodation/application') }}</div>
    <div class="mt-2">
        <label for="city">{{ __('City') }}</label>
        <input id="city" type="text" class="form-control" name="city" autocomplete="city" autofocus>
        <span class="invalid-feedback" role="alert">
            <strong id="errorCity"></strong>
        </span>
    </div>
    <div class="mt-2">
        <label for="arrival">{{ __('Arrival') }}</label>
        <input id="arrival" type="date" class="form-control" name="arrival" autocomplete="arrival" autofocus>
        <span class="invalid-feedback" role="alert">
            <strong id="errorArrival"></strong>
        </span>
    </div>
    <div class="mt-2">
        <label for="departure">{{ __('Departure') }}</label>
        <input id="departure" type="date" class="form-control" name="departure" autocomplete="departure" autofocus>
        <span class="invalid-feedback" role="alert">
            <strong id="errorDeparture"></strong>
        </span>
    </div>
</div>

<div class="text-left mb-4">
    <div class="form-check">
        <input class="form-check-input" type="radio" name="searchAppsHouses" id="houses" value="{{\App\BookingHistory::MY_ACCOMMODATION}}">
        <label class="form-check-label" for="houses">
            {{ __('My accommodation') }}
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="searchAppsHouses" id="apps" value="{{\App\BookingHistory::MY_APPLICATIONS}}">
        <label class="form-check-label" for="apps">
            {{ __('My applications') }}
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="searchAppsHouses" id="all" value="{{\App\BookingHistory::ALL}}" checked>
        <label class="form-check-label" for="all">
            {{ __('All') }}
        </label>
    </div>
</div>

<div class="form-check text-left mb-4">
    <div class="text-center font-weight-bold mb-2">{{ __('Action') }}</div>
    <div>
        <input class="form-check-input" type="checkbox" value="{{\App\Booking::STATUS_BOOKING_SEND}}" id="sent" name="statuses[]" checked>
        <label class="form-check-label" for="sent">
            {{ __('Sent') }}
        </label>
    </div>
    <div>
        <input class="form-check-input" type="checkbox" value="{{\App\Booking::STATUS_BOOKING_ACCEPT}}" id="accepted" name="statuses[]" checked>
        <label class="form-check-label" for="accepted">
            {{ __('Accepted') }}
        </label>
    </div>
    <div>
        <input class="form-check-input" type="checkbox" value="{{\App\Booking::STATUS_BOOKING_REJECT}}" id="declined" name="statuses[]" checked>
        <label class="form-check-label" for="declined">
            {{ __('Declined') }}
        </label>
    </div>
    <div>
        <input class="form-check-input" type="checkbox" value="{{\App\Booking::STATUS_BOOKING_CANCEL}}" id="cancelled" name="statuses[]" checked>
        <label class="form-check-label" for="cancelled">
            {{ __('Cancelled') }}
        </label>
    </div>
    <div>
        <input class="form-check-input" type="checkbox" value="{{\App\Booking::STATUS_BOOKING_SEND_BACK}}" id="sentback" name="statuses[]" checked>
        <label class="form-check-label" for="sentback">
            {{ __('Sent back') }}
        </label>
    </div>
    <div>
        <input class="form-check-input" type="checkbox" value="{{\App\Booking::STATUS_BOOKING_DELETE}}" id="deleted" name="statuses[]" checked>
        <label class="form-check-label" for="deleted">
            {{ __('Deleted') }}
        </label>
    </div>
</div>

<div class="text-left mb-4">
    <div class="text-center font-weight-bold mb-2">{{ __('Type') }}</div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="searchOutIn" id="outgoing" value="{{\App\BookingHistory::OUTGOING}}">
        <label class="form-check-label" for="outgoing">
            {{ __('Outgoing') }}
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="searchOutIn" id="incoming" value="{{\App\BookingHistory::INCOMING}}">
        <label class="form-check-label" for="incoming">
            {{ __('Incoming') }}
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="searchOutIn" id="allOutIn" value="{{\App\BookingHistory::ALL_OUT_IN}}" checked>
        <label class="form-check-label" for="allOutIn">
            {{ __('All') }}
        </label>
    </div>
</div>

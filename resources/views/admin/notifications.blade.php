@foreach($notifications as $notification)
    <div class="row mb-2 text-secondary">

        <div class="col-4 font-weight-bold h5">
            <a class="text-secondary" href="{{ route('profile.show', $notification->data['id']) }}">
                {{$notification->data['name']}} {{$notification->data['surname']}}
            </a>
        </div>

        <div class="col-4 h5">{{$notification->data['email']}}</div>

        <div class="col-4 h5 text-center">
            <form action="{{ route('admin.mark-as-read') }}" method="post">
                @csrf
                <input type="hidden" name="notificationId" value="{{$notification->id}}">
                <button class="btn btn-outline-secondary btn-sm">{{ __('Mark As Read') }}</button>
            </form>
        </div>

    </div>
@endforeach

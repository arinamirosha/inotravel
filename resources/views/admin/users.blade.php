@foreach($users as $user)

    <div class="row mb-2">
        <div class="col-4 font-weight-bold h5">
            {{ $user->name }} {{ $user->surname }}
        </div>
        <div class="col-4 h5">
            {{ $user->email }}
        </div>
        <div class="col-4 h5 text-center">
            @if($user->admin)
                {{ __('Administrator') }}
            @else
                <form action="{{ route('admin.update', $user->id) }}" method="post">
                    @csrf
                    <button class="btn btn-outline-secondary btn-sm">{{ __('Make admin') }}</button>
                </form>
            @endif
        </div>
    </div>

@endforeach

<div class="row offset-1">
    <div class="col-6">
        {{$users->links()}}
    </div>
</div>

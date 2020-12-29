<?php $isSuperAdmin = Auth::user()->admin == 2 ?>

@foreach($users as $user)

    <div class="row mb-2 @if($user->admin) text-primary @endif">
        <div class="col-4 font-weight-bold h5">
            {{ $user->name }} {{ $user->surname }}
        </div>
        <div class="col-4 h5">
            {{ $user->email }}
        </div>
        <div class="col-4 h5 text-center">
            @if($isSuperAdmin && $user->admin != 2)
                <form action="{{ route('admin.update', $user->id) }}" method="post">
                    @csrf
                    @if($user->admin)
                        <button class="btn btn-outline-danger btn-sm">{{ __('Remove admin') }}</button>
                    @elseif($isSuperAdmin)
                        <button class="btn btn-outline-success btn-sm">{{ __('Make admin') }}</button>
                    @endif
                </form>
            @else
                @switch($user->admin)
                    @case(0) {{ __('No Admin') }} @break
                    @case(1) {{ __('Administrator') }} @break
                    @case(2) {{ __('Super Admin') }} @break
                @endswitch
            @endif
        </div>
    </div>

@endforeach

<div class="row offset-1">
    <div class="col-6">
        {{$users->links()}}
    </div>
</div>

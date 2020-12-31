<?php $isUserSuperAdmin = Auth::user()->admin == \App\User::SUPER_ADMIN ?>

@forelse($users as $user)

    <?php $isNoAdmin = $user->admin == \App\User::NO_ADMIN ?>
    <?php $isAdmin = $user->admin != \App\User::NO_ADMIN ?>
    <?php $isSuperAdmin = $user->admin == \App\User::SUPER_ADMIN ?>

    <div class="row mb-2 @if($isAdmin) text-primary @endif">
        <div class="col-4 font-weight-bold h5">
            <a @if($isNoAdmin) class="text-dark" @endif href="{{ route('profile.show', $user->id) }}">{{ $user->name }} {{ $user->surname }}</a>
        </div>
        <div class="col-4 h5">
            {{ $user->email }}
        </div>
        <div class="col-4 h5 text-center">
            @if($isUserSuperAdmin && !$isSuperAdmin)
                <form action="{{ route('admin.update', $user->id) }}" method="post">
                    @csrf
                    @if($isAdmin)
                        <button class="btn btn-outline-danger btn-sm">{{ __('Remove admin') }}</button>
                    @elseif($isUserSuperAdmin)
                        <button class="btn btn-outline-success btn-sm">{{ __('Make admin') }}</button>
                    @endif
                </form>
            @else
                @switch($user->admin)
                    @case(\App\User::NO_ADMIN) {{ __('No Admin') }} @break
                    @case(\App\User::ADMIN) {{ __('Administrator') }} @break
                    @case(\App\User::SUPER_ADMIN) {{ __('Super Admin') }} @break
                @endswitch
            @endif
        </div>
    </div>

@empty
    <div class="row justify-content-center p-3 h4">
        {{ __('Not Found') }}
    </div>
@endforelse

<div class="row offset-1">
    <div class="col-6">
        {{$users->links()}}
    </div>
</div>

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // if not auth, go to register
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('profiles.edit', compact('user'));
    }

    public function update(User $user, ProfileRequest $request)
    {
        try {
            $user->update($request->all());
            $message = "Данные успешно обновлены";
        } catch(QueryException $e) {
            $message = "Указанная почта привязана к другому аккаунту";
        }

        return redirect(route('profile.edit', $user->id))->with('message', $message);
    }

    public function updatePassword(PasswordRequest $request, User $user)
    {
        if (Hash::check($request->password_old, $user->password)) {
            $newPassword = Hash::make($request->password);
            $user->update(['password' => $newPassword]);
            $message = "Пароль успешно обновлен";
        }
        else $message = "Старый пароль не соответствует";

        return redirect(route('profile.edit', $user->id))->with('message', $message);
    }

    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = User::all()->sortBy('name');
        return view('profiles.index', compact('users'));
    }

    public function makeAdmin(User $user)
    {
        $this->authorize('viewAny', User::class);
        $user->update(['admin' => true]);
        return redirect(route('profiles.index'));
    }

}

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
    /**
     * ProfilesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show page for updating profile
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit()
    {
        $user = Auth::user();
        $this->authorize('update', $user);

        return view('profiles.edit', compact('user'));
    }

    /**
     * Update user, get exception when user trying to change email that another user has
     *
     * @param ProfileRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        try {
            $user->update($request->all());
            $message = "Данные успешно обновлены";
        } catch (QueryException $e) {
            $message = "Указанная почта привязана к другому аккаунту";
        }

        return redirect(route('profile.edit'))->with('message', $message);
    }

    /**
     * Change password
     *
     * @param PasswordRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updatePassword(PasswordRequest $request)
    {
        $user = Auth::user();
        if (Hash::check($request->passwordOld, $user->password)) {
            $newPassword = Hash::make($request->password);
            $user->update(['password' => $newPassword]);
            $message = "Пароль успешно обновлен";
        } else {
            $message = "Старый пароль не соответствует";
        }

        return redirect(route('profile.edit'))->with('message', $message);
    }

}

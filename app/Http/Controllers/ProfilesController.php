<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
            $message = __('Profile updated successfully');
        } catch (QueryException $e) {
            $message = App::isLocale('ru') ?
                __('messages.email_busy') :
                __('validation.unique', ['attribute' => 'email']);
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
            $message = __('Password updated successfully');
        } else {
            $message = __('Old password does not match');
        }

        return redirect(route('profile.edit'))->with('message', $message);
    }

}

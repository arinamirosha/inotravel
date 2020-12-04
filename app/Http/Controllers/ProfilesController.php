<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\TemporaryImage;
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
     * Update user
     *
     * @param ProfileRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $user->update($request->all());

        return redirect(route('profile.edit'))->with('message', __('Profile updated successfully'));
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
        $newPassword = Hash::make($request->password);
        $user->update(['password' => $newPassword]);

        return redirect(route('profile.edit'))->with('message', __('Password updated successfully'));
    }

    /**
     * Upload avatar by ajax
     *
     * @param ImageRequest $request
     * @return TemporaryImage
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function uploadAvatar(ImageRequest $request)
    {
        $user = Auth::user();
        $user->deleteAvatar();

        if ($request->has('delete')) {
            return true;
        }

        $imgPath = storeImage($request->file);
        $user->update(['avatar' => $imgPath]);

        return ['image' => $imgPath];
    }
}

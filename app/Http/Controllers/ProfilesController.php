<?php

namespace App\Http\Controllers;

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
        try
        {
            $user->update($request->all());
            $message = "Данные успешно обновлены";
        }
        catch(QueryException $e)
        {
            $message = "Указанная почта привязана к другому аккаунту";
        }

        return redirect(route('profile.edit', $user->id))->with('message', $message);
    }


}

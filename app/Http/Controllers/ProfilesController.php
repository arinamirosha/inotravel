<?php

namespace App\Http\Controllers;

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
        // Если не на своей странице, то на главную
//        return (auth()->user() == $user) ?
//            view('profiles.edit', compact('user')) :
//            redirect('/');
    }

    public function update(User $user)
    {
        $data = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $data['name'] = ucfirst($data['name']); // только первая, потому что могут быть двойные имена/фамилии
        $data['surname'] = ucfirst($data['surname']);

        try
        {
            auth()->user()->update($data);
            $message = "Данные успешно обновлены";
        }
        catch(QueryException $e)
        {
            $message = "Указанная почта привязана к другому аккаунту";
        }

        return redirect("/profile/{$user->id}/edit")->with('message', $message);
    }


}

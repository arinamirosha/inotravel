<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordsController extends Controller
{
    public function update(User $user)
    {
        $data = request()->validate([
            'password_old' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (Hash::check($data['password_old'], $user->password)) {
            $data['password'] = Hash::make($data['password']);
            $user->update($data);
            $message = "Пароль успешно обновлен";
        }
        else $message = "Старый пароль не соответствует";

        return redirect(route('profile.edit', $user->id))->with('message', $message);
    }
}

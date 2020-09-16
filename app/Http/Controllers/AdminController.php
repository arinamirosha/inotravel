<?php

namespace App\Http\Controllers;

use App\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $users = User::all()->sortBy('name');
        return view('admin.index', compact('users'));
    }

    public function update(User $user)
    {
        $user->update(['admin' => true]);
        return redirect(route('admin.index'));
    }
}

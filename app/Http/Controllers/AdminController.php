<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show all users
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $users = User::orderBy('name')->paginate(25);

        if ($request->ajax()) {
            return view('admin.users', compact('users'));
        }

        $notifications = Auth::user()->unreadNotifications;

        return view('admin.index', compact('users', 'notifications'));
    }

    public function markAsRead(Request $request)
    {
        $notificationId = $request->notificationId;
        if ($notificationId) {
            Auth::user()->unreadNotifications->where('id', '=', $notificationId)->markAsRead();
        } else {
            Auth::user()->unreadNotifications->markAsRead();
        }

        return redirect(route('admin.index'));
    }

    /**
     * Make user an admin
     *
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(User $user)
    {
        $user->admin ?
            $user->update(['admin' => false]) :
            $user->update(['admin' => true]);

        return redirect(route('admin.index'));
    }
}

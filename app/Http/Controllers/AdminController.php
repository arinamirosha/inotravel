<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchUserRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(SearchUserRequest $request)
    {
        $users = User::orderBy('name');

        if ($request->ajax())
        {
            if ($request->searchUserData)
            {
                $users = $users->where(DB::raw("concat(name,' ',surname)"), "like", "%$request->searchUserData%");
            }

            $selectUser = $request->selectUser;
            if ($selectUser != User::ALL)
            {
                $users = $users->where('admin', '=', $selectUser);
            }
        }

        $users = $users->paginate(25);

        if ($request->ajax())
        {
            return view('admin.users', compact('users'));
        }

        $notifications = Auth::user()->unreadNotifications;

        return view('admin.index', compact('users', 'notifications'));
    }

    public function markAsRead(Request $request)
    {
        if ($request->has('notificationId'))
        {
            Auth::user()->unreadNotifications->where('id', '=', $request->notificationId)->markAsRead();
        }
        else
        {
            Auth::user()->unreadNotifications->markAsRead();
        }

        return;
    }

    /**
     * Make user an admin
     *
     * @param User $user
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(User $user)
    {
        $user->update(['admin' => $user->admin ? User::NO_ADMIN : User::ADMIN]);

        return redirect(route('admin.index'));
    }
}

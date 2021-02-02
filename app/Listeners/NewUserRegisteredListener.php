<?php

namespace App\Listeners;

use App\Notifications\NewUserNotification;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NewUserRegisteredListener
{
    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        // Notify admins and super admins about new user
        $users = User::where('admin', '<>', User::NO_ADMIN)->get();
        Notification::send($users, new NewUserNotification($event->user));
    }
}

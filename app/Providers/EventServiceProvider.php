<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Providers;

use App\Events\BookingSentEvent;
use App\Events\BookingAnswerEvent;
use App\Events\BookingCancelledEvent;
use App\Events\BookingSentBackEvent;
use App\Events\BookingDeletedEvent;
use App\Listeners\BookingSentListener;
use App\Listeners\BookingAnswerListener;
use App\Listeners\BookingCancelledListener;
use App\Listeners\BookingSentBackListener;
use App\Listeners\BookingDeletedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        BookingSentEvent::class => [
            BookingSentListener::class,
        ],
        BookingAnswerEvent::class => [
            BookingAnswerListener::class,
        ],
        BookingCancelledEvent::class => [
            BookingCancelledListener::class,
        ],
        BookingSentBackEvent::class => [
            BookingSentBackListener::class,
        ],
        BookingDeletedEvent::class => [
            BookingDeletedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

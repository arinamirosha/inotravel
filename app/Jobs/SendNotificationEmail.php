<?php

namespace App\Jobs;

use App\House;
use App\Mail\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $booksToMail;
    /**
     * @var House
     */
    private $house;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($booksToMail, House $house)
    {
        //
        $this->booksToMail = $booksToMail;
        $this->house = $house;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->booksToMail as $booking)
            Mail::to($booking->user->email)->send(new Notification($booking, $this->house)); //приходит на mailtrap
    }
}

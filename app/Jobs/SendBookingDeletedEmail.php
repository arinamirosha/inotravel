<?php

namespace App\Jobs;

use App\House;
use App\Mail\BookingDeletedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBookingDeletedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    const JOB_NAME = 'booking_notifications_job';
    private $booksToMail;
    private $name;
    private $city;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($booksToMail, $name, $city)
    {
        $this->booksToMail = $booksToMail;
        $this->name = $name;
        $this->city = $city;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->booksToMail as $booking) {
            Mail::to($booking->email)->send(new BookingDeletedNotification(
                $booking->arrival,
                $booking->departure,
                $this->name,
                $this->city
            ));
        }
    }

    /**
     * The job failed to process.
     *
     * @param \Exception $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        Log::error($exception->getMessage(), ['job_name' => self::JOB_NAME]);
    }
}

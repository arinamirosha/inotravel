<?php

namespace App\Jobs;

use App\Booking;
use App\Mail\NewBookingMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewBookingEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    const JOB_NAME = 'new_booking_notifications_job';
    private $booking;
    private $email;

    /**
     * Create a new job instance.
     *
     * @param $email
     * @param Booking $booking
     */
    public function __construct($email, Booking $booking)
    {
        $this->booking = $booking;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new NewBookingMail($this->booking));
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

<?php

namespace App\Mail;

use App\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookingChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    private $booking;

    /**
     * Create a new message instance.
     *
     * @param Booking $booking
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Build the message about changing of booking.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('Application updated'))->markdown('email.booking_changed',
            ['booking' => $this->booking]);
    }
}

<?php

namespace App\Mail;

use App\Booking;
use App\House;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingDeletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $booking;
    private $name;
    private $city;

    public function __construct(Booking $booking, $name, $city)
    {
        $this->booking = $booking;
        $this->name = $name;
        $this->city = $city;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Заявка удалена')
            ->view('email.booking_deleted_notification');
    }
}

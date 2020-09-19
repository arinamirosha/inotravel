<?php

namespace App\Mail;

use App\Booking;
use App\House;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $booking;
    public $house;

    public function __construct(Booking $booking, House $house)
    {
        $this->booking = $booking;
        $this->house = $house;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Заявка удалена')
            ->view('email.notification');
    }
}

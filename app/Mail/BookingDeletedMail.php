<?php

namespace App\Mail;

use App\Booking;
use App\House;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookingDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $arrival;
    private $departure;
    private $name;
    private $city;

    public function __construct($arrival, $departure, $name, $city)
    {
        $this->arrival = $arrival;
        $this->departure = $departure;
        $this->name = $name;
        $this->city = $city;
    }

    /**
     * Build the message about deleting of booking.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('Application deleted'))
            ->view('email.booking_deleted', [
                'arrival' => $this->arrival,
                'departure' => $this->departure,
                'name' => $this->name,
                'city' => $this->city,
            ]);
    }
}

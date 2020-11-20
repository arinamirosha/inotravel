<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Booking;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Booking::class, function (Faker $faker) {

    $arrival = $faker->dateTimeBetween('-3 months', '+3 months')->format("Y-m-d");
    $departure = Carbon::parse($arrival)->addDays(rand(1, 10));

    $statuses = [
        Booking::STATUS_BOOKING_SEND,
        Booking::STATUS_BOOKING_SEND_BACK,
        Booking::STATUS_BOOKING_ACCEPT,
        Booking::STATUS_BOOKING_REJECT,
        Booking::STATUS_BOOKING_CANCEL
    ];
    $status = $faker->randomElement($statuses);

    $new = in_array($status, [Booking::STATUS_BOOKING_SEND, Booking::STATUS_BOOKING_SEND_BACK])
        ? Booking::STATUS_BOOKING_VIEWED
        : $faker->randomElement([Booking::STATUS_BOOKING_NEW, Booking::STATUS_BOOKING_VIEWED]);

    switch ($status) {
        case Booking::STATUS_BOOKING_SEND:
        case Booking::STATUS_BOOKING_ACCEPT:
            $deleted_at = null;
            break;
        case Booking::STATUS_BOOKING_SEND_BACK:
            $deleted_at = now();
            break;
        case Booking::STATUS_BOOKING_REJECT:
        case Booking::STATUS_BOOKING_CANCEL:
            $deleted_at = $new == Booking::STATUS_BOOKING_NEW ? null : $faker->randomElement([now(), null]);
            break;
    }

    return [
        'status' => $status,
        'new' => $new,
        'arrival' => $arrival,
        'departure' => $departure,
        'people' => $faker->numberBetween(1, 5),
        'deleted_at' => $deleted_at,
    ];
});

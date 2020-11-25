<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Booking;
use App\BookingHistory;
use App\User;
use Faker\Generator as Faker;

$factory->define(BookingHistory::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement([
            BookingHistory::TYPE_SENT,
            BookingHistory::TYPE_RECEIVED,
            BookingHistory::TYPE_ACCEPTED,
            BookingHistory::TYPE_ACCEPTED_ANSWER,
            BookingHistory::TYPE_REJECTED,
            BookingHistory::TYPE_REJECTED_ANSWER,
            BookingHistory::TYPE_CANCELLED,
            BookingHistory::TYPE_CANCELLED_INFO,
            BookingHistory::TYPE_SENT_BACK,
            BookingHistory::TYPE_SENT_BACK_INFO,
            BookingHistory::TYPE_DELETED,
            BookingHistory::TYPE_DELETED_INFO
        ]),
        'created_at' => $faker->dateTimeBetween('-3 months', 'now')->format("Y-m-d"),
    ];
});

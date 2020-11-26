<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Booking;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Booking::class, function (Faker $faker) {

    $arrival = $faker->dateTimeBetween('-3 months', '+3 months')->format("Y-m-d");
    $departure = Carbon::parse($arrival)->addDays(rand(1, 15));

    $statuses = [
        Booking::STATUS_BOOKING_SEND,
        Booking::STATUS_BOOKING_SEND_BACK,
        Booking::STATUS_BOOKING_ACCEPT,
        Booking::STATUS_BOOKING_REJECT,
        Booking::STATUS_BOOKING_CANCEL
    ];
    $status = $faker->randomElement($statuses);

    // booking with STATUS_BOOKING_SEND must be viewed, the rest doesn't matter
    $new = $status == Booking::STATUS_BOOKING_SEND
        ? Booking::STATUS_BOOKING_VIEWED
        : $faker->randomElement([Booking::STATUS_BOOKING_NEW, Booking::STATUS_BOOKING_VIEWED]);

    // booking with STATUS_BOOKING_SEND must be soft deleted, the rest doesn't matter
    $nullOrNow = $faker->boolean(70) ? null : now();
    $deleted_at = $status == Booking::STATUS_BOOKING_SEND_BACK ? now() : $nullOrNow;

    return [
        'status' => $status,
        'new' => $new,
        'arrival' => $arrival,
        'departure' => $departure,
        'people' => $faker->numberBetween(1, 5),
        'deleted_at' => $deleted_at,
    ];
});

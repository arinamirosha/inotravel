<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\House;
use Faker\Generator as Faker;

$factory->define(House::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(5),
        'city' => $faker->city,
        'address' => $faker->streetAddress,
        'places' => $faker->numberBetween(1, 10),
        'info' => $faker->text(500),
//        'image' => $faker->,
    ];
});

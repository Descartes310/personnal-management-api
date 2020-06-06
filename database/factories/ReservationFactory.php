<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Reservation;
use Faker\Generator as Faker;

$factory->define(Reservation::class, function (Faker $faker) {
    return [
        'reservation_date' => $reservation_date = $faker->date,
        'days' => $days = $faker->number_format,
        'client_name' => $client_name = $faker->sentence,
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Room;
use Faker\Generator as Faker;

$factory->define(Room::class, function (Faker $faker) {
    return [
        'room_number' => $room_number = $faker->number_format,
        'room_state' => $room_state = $faker->randomElement(['REJECTED','PENDING','ACCEPTED']),
        'status' => $status = $faker->randomElement(['REJECTED','PENDING','ACCEPTED']),        
        'amount' => $amount = $faker->double,
    ];
});

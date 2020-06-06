<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Hotel;
use Faker\Generator as Faker;

$factory->define(Hotel::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->sentence,
        'slug' => $slug = $faker->sentence,
        'class' => $class = $faker->sentence,
        'location' => $location = $faker->sentence,
        'director_name' => $director_name = $faker->sentence,
        'room_total_number' => $room_total_number = $faker->number_format,
    ];
});

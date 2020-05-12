<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Division;
use Faker\Generator as Faker;

$factory->define(Division::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->sentence,
        'slug' => $name = $faker->sentence,
        'description' => $faker->paragraph(),
    ];
});

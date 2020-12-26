<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\School::class, function (Faker $faker) {
    return [
        'name' => $faker->name . '学校',
        'approve_time' => time() - 100,
    ];
});

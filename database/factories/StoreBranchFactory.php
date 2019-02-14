<?php

use Faker\Generator as Faker;

$factory->define(App\Models\StoreBranch::class, function (Faker $faker) {

    $data = [
        'name'=> $faker->name,
        'parent' => 1,
    ];

    return $data;

});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'description' => $faker->realText(60),
        'price' => $faker->randomFloat(2, 10, 100),
        'stock' => $faker->numberBetween(1, 100),
    ];
});

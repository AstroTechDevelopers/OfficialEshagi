<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'creator'                              => function(){return \App\Models\User::where('utype','Partner')->inRandomOrder()->first()->name;},
        'pcode'                        => $faker->countryCode,
        'serial'                         => $faker->phoneNumber,
        'pname'                             => $faker->name,
        'model'                             => $faker->streetName,
        'descrip'                             => $faker->sentence(6),
        'price'                             => $faker->randomFloat(2,10000,null),
    ];
});

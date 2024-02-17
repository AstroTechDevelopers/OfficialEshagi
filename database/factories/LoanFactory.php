<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Loan;
use Faker\Generator as Faker;

$factory->define(Loan::class, function (Faker $faker) {
    return [
        'user_id'                              => function(){return \App\Models\User::where('utype','Client')->inRandomOrder()->first()->id;},
        'client_id'                        => function(){return \App\Models\Client::inRandomOrder()->first()->id;},
        'partner_id'                         => function(){return \App\Models\Partner::inRandomOrder()->first()->id;},
        'channel_id'                             => 'www.eshagi.com',
        'funder_id'                             => $faker->randomDigit,
        'funder_acc_number'                             => $faker->bankAccountNumber,
        'loan_type'                             => rand(1,4),
        'loan_status'                             => rand(0,14),
        'amount'                             => $faker->randomFloat(2,15000,null),
        'paybackPeriod'                             => rand(2,24),
        'interestRate'                             => 12,
        'monthly'                             => $faker->randomFloat(2,2000,null),
        'disbursed'                             => $faker->randomFloat(2,12000,null),
        'appFee'                             => $faker->randomFloat(2,600,null),
        'charges'                             => $faker->randomFloat(2,700,null),
        'product'                             => function(){return \App\Models\Product::inRandomOrder()->first()->pcode;},
        'pprice'                             => $faker->randomFloat(2,10000,null),
    ];
});

<?php

use jeremykenedy\LaravelRoles\Models\Role;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;
    $userRole = Role::whereName('User')->first();

    return [
        'name'                           => $faker->unique()->userName,
        'first_name'                     => $faker->firstName,
        'last_name'                      => $faker->lastName,
        'email'                          => $faker->unique()->safeEmail,
        'password'                       => $password ?: $password = bcrypt('secret'),
        'token'                          => str_random(64),
        'activated'                      => true,
        'remember_token'                 => str_random(10),
        'signup_ip_address'              => $faker->ipv4,
        'signup_confirmation_ip_address' => $faker->ipv4,
    ];
});

$factory->define(App\Models\Profile::class, function (Faker\Generator $faker) {
    return [
        'user_id'          => factory(App\Models\User::class)->create()->id,
        'theme_id'         => 1,
        'location'         => $faker->streetAddress,
        'bio'              => $faker->paragraph(2, true),
        'twitter_username' => $faker->userName,
        'github_username'  => $faker->userName,
    ];
});

$factory->define(App\Models\Product::class, function (Faker\Generator $faker) {
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

$factory->define(App\Models\Loan::class, function (Faker\Generator $faker) {
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

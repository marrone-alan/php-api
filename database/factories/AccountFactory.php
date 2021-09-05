<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Account;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'balance' => $faker->randomDigit,
    ];
});

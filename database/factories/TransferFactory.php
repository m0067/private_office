<?php

declare(strict_types=1);

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Transfer;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Transfer::class, function (Faker $faker) {
    return [
        'sender_id'    => $faker->randomElement(UsersTableSeeder::USERS_ID),
        'recipient_id' => $faker->randomElement(UsersTableSeeder::USERS_ID),
        'amount'       => $faker->numberBetween(1000, 100000),
    ];
});

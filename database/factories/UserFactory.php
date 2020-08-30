<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
  return [
    'name' => $faker->name,
    'email' => $faker->unique()->safeEmail,
    'email_verified_at' => now(),
    'password' => \Illuminate\Support\Facades\Hash::make('password'),
    'remember_token' => \Illuminate\Support\Str::random(8)
  ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Phone;
use Faker\Generator as Faker;

$factory->define(Phone::class, function (Faker $faker) {
  return [
    'phone_number' => $faker->e164PhoneNumber,
    'phone_type_id' => rand(1, 4)
  ];
});

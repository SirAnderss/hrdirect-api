<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Picture;
use Faker\Generator as Faker;

$factory->define(Picture::class, function (Faker $faker) {
  return [
    'picture_link' => $faker->imageUrl(600, 400, 'animals', true, 'Faker'),
    'picture_type_id' => rand(1, 4)
  ];
});

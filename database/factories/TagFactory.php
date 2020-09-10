<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
  $name = $faker->unique()->domainWord;
  $slug = \Illuminate\Support\Str::slug($name, '-');

  return [
    'name' => $name,
    'slug' => $slug
  ];
});

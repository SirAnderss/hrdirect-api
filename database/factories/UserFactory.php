<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$factory->define(User::class, function () {
  return [
    'name' => 'SirAnderss',
    'email' => 'a@a.co',
    'email_verified_at' => now(),
    'password' => Hash::make('password'),
    'remember_token' => Str::random(8)
  ];
});

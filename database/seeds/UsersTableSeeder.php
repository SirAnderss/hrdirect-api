<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    App\User::create([
      'name' => 'Andersson',
      'email' => 'a@a.co',
      'email_verified_at' => now(),
      'password' => \Illuminate\Support\Facades\Hash::make('password'),
      'remember_token' => \Illuminate\Support\Str::random(8)
    ]);
    // factory(\App\User::class, 5)->create();
  }
}

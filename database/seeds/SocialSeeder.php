<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(Faker $faker)
  {

    $profiles = App\Profile::all();

    foreach ($profiles as $profile) {
      DB::table('socials')->insert(array(
        0 =>
        array(
          'link' => $faker->url,
          'profile_id' => $profile->id,
        ),
        1 =>
        array(
          'link' => $faker->url,
          'profile_id' => $profile->id,
        ),
        2 =>
        array(
          'link' => $faker->url,
          'profile_id' => $profile->id,
        ),
        3 =>
        array(
          'link' => $faker->url,
          'profile_id' => $profile->id,
        ),
        4 =>
        array(
          'link' => $faker->url,
          'profile_id' => $profile->id,
        ),
      ));
    }
  }
}

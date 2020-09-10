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
      $num_socials = rand(3, 7);

      for ($i = 0; $i < $num_socials; $i++) {
        DB::table('socials')->insert(array(
          'link' => $faker->unique()->url,
          'profile_id' => $profile->id,
        ));
      }
    }
  }
}

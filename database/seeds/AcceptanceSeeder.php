<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcceptanceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    $profiles = App\Profile::all();
    $users = App\User::all();
    $profile_arr = array();

    foreach ($profiles as $profile) {
      array_push($profile_arr, $profile->id);
    }

    foreach ($users as $user) {
      $num_acceptances = rand(1, 5);

      for ($i = 0; $i < $num_acceptances; $i++) {
        $profile_rand = array_rand(array_flip($profile_arr), 1);

        DB::table('acceptances')->insert(array(
          'rating' => rand(1, 5),
          'avg_rating' => rand(1, 5),
          'profile_id' => $profile_rand,
          'user_id' => $user->id,
          'created_at' => now(),
          'updated_at' => now(),
        ));
      }
    }
  }
}

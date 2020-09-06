<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(Faker $faker)
  {

    $profiles = App\Profile::all();
    $users = App\User::all();
    $profile_arr = array();

    foreach ($profiles as $profile) {
      array_push($profile_arr, $profile->id);
    }

    foreach ($users as $user) {
      $num_comments = rand(2, 10);

      for ($i = 0; $i < $num_comments; $i++) {
        $profile_rand = array_rand(array_flip($profile_arr), 1);

        DB::table('comments')->insert(array(
          'id' => Str::uuid(),
          'comment' => $faker->text(rand(200, 500)),
          'profile_id' => $profile_rand,
          'user_id' => $user->id,
          'created_at' => now(),
          'updated_at' => now(),
        ));
      }
    }
  }
}

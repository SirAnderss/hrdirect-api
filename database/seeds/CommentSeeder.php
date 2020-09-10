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
      $num_comments = rand(10, 20);

      for ($i = 0; $i < $num_comments; $i++) {
        $profile_rand = array_rand(array_flip($profile_arr), 1);

        $rating = \App\Comment::where('profile_id', $profile_rand)->get('rating');

        $rand = rand(1, 5);

        if (count($rating) == 0) {
          $avg = $rand;
        } else {
          $temp = 0;
          $count = count($rating);

          foreach ($rating as $key => $value) {
            $temp =  $temp + $value->rating;
          }

          $avg = $temp / $count;
        }

        DB::table('comments')->insert(array(
          'id' => Str::uuid(),
          'comment' => $faker->text(rand(200, 500)),
          'rating' => $rand,
          'avg_rating' => $avg,
          'profile_id' => $profile_rand,
          'user_id' => $user->id,
          'created_at' => now(),
          'updated_at' => now(),
        ));

        $temp = 0;
      }
    }
  }
}

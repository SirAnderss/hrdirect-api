<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(Faker $faker)
  {
    factory(\App\User::class, 50)->create();

    $users = App\User::all();
    $arr = array();
    $rand_value = 1;

    foreach ($users as $user) {
      array_push($arr, $user->id);
      $rand_item = array_rand(array_flip($arr), $rand_value);
      $name = $faker->company;
      $slug = Str::slug($name, '-');
      if ($rand_value < 2) {
        DB::table('profiles')->insert(array(
          'id' => Str::uuid(),
          'name' => $name,
          'slug' => $slug,
          'address' => $faker->address,
          'description' => $faker->text(200),
          'user_id' => $rand_item,
          'created_at' => now(),
          'updated_at' => now(),
        ));
        $rand_value = rand(1, 3);
      } else {
        foreach ($rand_item as $item) {
          DB::table('profiles')->insert(array(
            'id' => Str::uuid(),
            'name' => $name,
            'slug' => $slug,
            'address' => $faker->address,
            'description' => $faker->text(200),
            'user_id' => $item,
            'created_at' => now(),
            'updated_at' => now(),
          ));
          $rand_value = rand(1, 3);
        }
      }
    }
  }
}

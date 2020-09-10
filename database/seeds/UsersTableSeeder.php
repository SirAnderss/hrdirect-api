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
    factory(\App\User::class, 150)->create();

    $users = App\User::all();
    $arr = array();

    foreach ($users as $user) {
      $name = $faker->unique()->company;
      $slug = Str::slug($name, '-');

      DB::table('profiles')->insert(array(
        'id' => Str::uuid(),
        'name' => $name,
        'slug' => $slug,
        'address' => $faker->address,
        'description' => $faker->text(200),
        'user_id' => $user->id,
        'created_at' => now(),
        'updated_at' => now(),
      ));
    }
  }
}

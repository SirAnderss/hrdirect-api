<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PictureSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(Faker $faker)
  {
    // factory(\App\Picture::class, 300)->create();

    $profiles = App\Profile::all();

    foreach ($profiles as $profile) {
      $num_images = rand(3, 6);

      DB::table('pictures')->insert(array(
        0 =>
        array(
          'id' => Str::uuid(),
          'picture_link' => $faker->imageUrl(600, 400, 'animals', true, 'Faker'),
          'picture_type_id' => 1,
          'profile_id' => $profile->id,
        ),
        1 =>
        array(
          'id' => Str::uuid(),
          'picture_link' => $faker->imageUrl(600, 400, 'animals', true, 'Faker'),
          'picture_type_id' => 2,
          'profile_id' => $profile->id,
        ),
        2 =>
        array(
          'id' => Str::uuid(),
          'picture_link' => $faker->imageUrl(200, 200, 'cats', true, 'Faker'),
          'picture_type_id' => 5,
          'profile_id' => $profile->id,
        ),
      ));

      for ($i = 0; $i < $num_images; $i++) {
        DB::table('pictures')->insert(array(
          0 =>
          array(
            'id' => Str::uuid(),
            'picture_link' => $faker->imageUrl(600, 400, 'animals', true, 'Faker'),
            'picture_type_id' => 3,
            'profile_id' => $profile->id,
          ),
          1 =>
          array(
            'id' => Str::uuid(),
            'picture_link' => $faker->imageUrl(600, 400, 'animals', true, 'Faker'),
            'picture_type_id' => 4,
            'profile_id' => $profile->id,
          )
        ));
      }
    }
  }
}

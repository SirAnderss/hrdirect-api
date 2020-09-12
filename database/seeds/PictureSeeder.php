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
      $num_images = rand(2, 4);

      DB::table('pictures')->insert(array(
        0 =>
        array(
          'id' => Str::uuid(),
          'picture_link' => 'http://hrdirect.me/storage/images/avatars/avatar.webp',
          'picture_type_id' => 1,
          'profile_id' => $profile->id,
        ),
        1 =>
        array(
          'id' => Str::uuid(),
          'picture_link' => 'http://hrdirect.me/storage/images/covers/cover_default.webp',
          'picture_type_id' => 2,
          'profile_id' => $profile->id,
        ),
        2 =>
        array(
          'id' => Str::uuid(),
          'picture_link' => 'http://hrdirect.me/storage/images/thumbs/thumb_avatar.webp',
          'picture_type_id' => 5,
          'profile_id' => $profile->id,
        ),
        3 =>
        array(
          'id' => Str::uuid(),
          'picture_link' => 'http://hrdirect.me/storage/images/thumbs/cover_thumb.webp',
          'picture_type_id' => 4,
          'profile_id' => $profile->id,
        ),
      ));

      for ($i = 0; $i < $num_images; $i++) {
        $rand = rand(1,4);
        DB::table('pictures')->insert(array(
          0 =>
          array(
            'id' => Str::uuid(),
            'picture_link' => 'http://hrdirect.me/storage/images/content_' . $rand . '.webp',
            'picture_type_id' => 3,
            'profile_id' => $profile->id,
          ),
          1 =>
          array(
            'id' => Str::uuid(),
            'picture_link' => 'http://hrdirect.me/storage/images/thumbs/content_thumb_' . $rand . '.webp',
            'picture_type_id' => 4,
            'profile_id' => $profile->id,
          )
        ));
      }
    }
  }
}

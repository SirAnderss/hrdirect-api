<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PictureTypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('picture_types')->insert(array(
      0 =>
      array(
        'name' => 'PROFILE',
      ),
      1 =>
      array(
        'name' => 'FRONT',
      ),
      2 =>
      array(
        'name' => 'CONTENT',
      ),
      3 =>
      array(
        'name' => 'THUMBNAIL',
      ),
      4 =>
      array(
        'name' => 'THUMBNAIL_PROFILE',
      ),
    ));
  }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhoneTypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('phone_types')->insert(array(
      0 =>
      array(
        'name' => 'MOBILE',
      ),
      1 =>
      array(
        'name' => 'HOME',
      ),
      2 =>
      array(
        'name' => 'WORK',
      ),
      3 =>
      array(
        'name' => 'OTHER',
      ),
    ));
  }
}

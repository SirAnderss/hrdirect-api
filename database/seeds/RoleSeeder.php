<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('roles')->insert(array(
      0 =>
      array(
        'name' => 'ROOT',
      ),
      1 =>
      array(
        'name' => 'PRIME',
      ),
      2 =>
      array(
        'name' => 'PARTNER',
      ),
      3 =>
      array(
        'name' => 'SUBCRIBER',
      ),
      4 =>
      array(
        'name' => 'VERIFIED',
      ),
      5 =>
      array(
        'name' => 'GUEST',
      ),
    ));
  }
}

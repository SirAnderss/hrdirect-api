<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
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
        'id' => Str::uuid(),
        'name' => 'ROOT',
      ),
      1 =>
      array(
        'id' => Str::uuid(),
        'name' => 'PRIME',
      ),
      2 =>
      array(
        'id' => Str::uuid(),
        'name' => 'PARTNER',
      ),
      3 =>
      array(
        'id' => Str::uuid(),
        'name' => 'SUBCRIBER',
      ),
      4 =>
      array(
        'id' => Str::uuid(),
        'name' => 'VERIFIED',
      ),
      5 =>
      array(
        'id' => Str::uuid(),
        'name' => 'REGISTERED',
      ),
    ));

    // $roles = App\Role::all();
    // $users = App\User::all();
    // $arr = array();

    // foreach ($roles as $role) {
    //   array_push($arr, $role->id);
    // }

    // foreach ($users as $user) {
    //   $rand = array_rand(array_flip($arr), 3);

    //   foreach ($rand as $item) {
    //     $user->roles()->attach($item);
    //   }
    // }
  }
}

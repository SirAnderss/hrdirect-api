<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    // $this->call(UsersTableSeeder::class);
    $this->call(RoleSeeder::class);
    $this->call(PictureTypeSeeder::class);
    $this->call(PhoneTypeSeeder::class);
    // $this->call(TagSeeder::class);
    // $this->call(CategorySeeder::class);
    // $this->call(PhoneSeeder::class);
    // $this->call(PictureSeeder::class);
    // $this->call(SocialSeeder::class);
    // $this->call(CommentSeeder::class);
    // $this->call(AcceptanceSeeder::class);
    // $this->call(ProfileSeeder::class);
  }
}

<?php

use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    $categories = App\Category::all();
    $tags = App\Tag::all();
    $phones = App\Phone::all();
    $profiles = App\Profile::all();

    $category_arr = array();
    $tag_arr = array();
    $phone_arr = array();

    foreach ($categories as $category) {
      array_push($category_arr, $category->id);
    }

    foreach ($tags as $tag) {
      array_push($tag_arr, $tag->id);
    }

    foreach ($phones as $phone) {
      array_push($phone_arr, $phone->id);
    }

    foreach ($profiles as $profile) {
      $category_rand = array_rand(array_flip($category_arr), rand(2, 5));
      $tag_rand = array_rand(array_flip($tag_arr), rand(3, 7));
      $phone_rand = array_rand(array_flip($phone_arr), rand(2, 4));

      foreach ($category_rand as $category_item) {
        $profile->categories()->attach($category_item);
      }

      foreach ($tag_rand as $tag_item) {
        $profile->tags()->attach($tag_item);
      }

      foreach ($phone_rand as $phone_item) {
        $profile->phones()->attach($phone_item);
      }
    }
  }
}

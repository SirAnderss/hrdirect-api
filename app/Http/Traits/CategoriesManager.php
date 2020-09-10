<?php

namespace App\Http\Traits;

use App\Category;
use Illuminate\Support\Str;

trait CategoriesManager
{
  private function insertCategories($categories, $item_profile)
  {
    try {
      foreach ($categories as $key => $category_item) {
        $category_name = Str::of($category_item)->trim();
        $category_slug = Str::slug($category_name, '-');

        if (Category::where('slug', $category_slug)->exists()) {
          $category = Category::where('slug', $category_slug)->get('id');

          $item_profile->categories()->attach($category[0]->id);
        } else {
          $category = new Category;
          $category->name = Str::lower($category_name);;
          $category->slug = $category_slug;
          $category->save();

          $item_profile->categories()->attach($category->id);
        }
      }

      return 'Success';
    } catch (\Throwable $th) {
      return 'Error';
      //throw $th;
    }
  }

  private function updateCategories($categories, $item_profile)
  {
    try {
      $new_categories = array();
      $old_categories = array();

      // Organize new categories
      foreach ($categories as $key => $temp_category) {
        array_push($new_categories, Str::lower($temp_category));
      }

      // Organize old categories
      foreach ($item_profile->categories as $key => $temp_category) {
        array_push($old_categories, $temp_category->name);
      }

      // Compare categories
      $category_intersect = array_intersect($new_categories, $old_categories);
      $categories_to_insert   = array_diff($new_categories, $category_intersect);
      $categories_to_delete   = array_diff($old_categories, $category_intersect);

      // Delete unused categories
      foreach ($categories_to_delete as $key => $category_item) {
        $category_slug = Str::slug($category_item, '-');
        $category = Category::where('slug', $category_slug)->get('id');

        $item_profile->categories()->detach($category[0]->id);
      }

      // Insert new categories
      foreach ($categories_to_insert as $key => $category_item) {
        $category_name = Str::of($category_item)->trim();
        $category_slug = Str::slug($category_name, '-');

        if (Category::where('slug', $category_slug)->exists()) {
          $category = Category::where('slug', $category_slug)->get('id');

          $item_profile->categories()->attach($category[0]->id);
        } else {
          $category = new Category;
          $category->name = $category_name;
          $category->slug = $category_slug;
          $category->save();

          $item_profile->categories()->attach($category->id);
        }
      }

      return 'Success';
    } catch (\Throwable $th) {
      return 'Error';
      //throw $th;
    }
  }
}

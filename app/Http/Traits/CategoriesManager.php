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
        $category_slug = Str::slug($category_item, '-');

        if (Category::where('slug', $category_slug)->exists()) {
          $category = Category::where('slug', $category_slug)->get('id');

          $item_profile->categories()->attach($category[0]->id);
        } else {
          $category = new Category;
          $category->name = $category_item;
          $category->slug = $category_slug;
          $category->save();

          $item_profile->categories()->attach($category->id);
        }
      }

      return 'Success';
    } catch (\Throwable $th) {
      return 'Category Error';
      //throw $th;
    }
  }
}

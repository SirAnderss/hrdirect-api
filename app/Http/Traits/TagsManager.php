<?php

namespace App\Http\Traits;

use App\Tag;
use Illuminate\Support\Str;

trait TagsManager
{

  protected function insertTags($tags, $item_profile)
  {
    try {
      foreach ($tags as $key => $tag_item) {
        $tag_slug = Str::slug($tag_item, '-');

        if (Tag::where('slug', $tag_slug)->exists()) {
          $tag = Tag::where('slug', $tag_slug)->get('id');

          $item_profile->tags()->attach($tag[0]->id);
        } else {
          $tag = new Tag;
          $tag->name = $tag_item;
          $tag->slug = $tag_slug;
          $tag->save();

          $item_profile->tags()->attach($tag->id);
        }
      }

      return 'Success';
    } catch (\Throwable $th) {
      return 'Tag Error';
      //throw $th;
    }
  }
}

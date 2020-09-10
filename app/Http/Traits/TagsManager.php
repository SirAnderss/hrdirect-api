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
        $tag_name = Str::of($tag_item)->trim();
        $tag_slug = Str::slug($tag_name, '-');

        if (Tag::where('slug', $tag_slug)->exists()) {
          $tag = Tag::where('slug', $tag_slug)->get('id');

          $item_profile->tags()->attach($tag[0]->id);
        } else {
          $tag = new Tag;
          $tag->name = Str::lower($tag_name);
          $tag->slug = $tag_slug;
          $tag->save();

          $item_profile->tags()->attach($tag->id);
        }
      }

      return 'Success';
    } catch (\Throwable $th) {
      return 'Eerror';
      //throw $th;
    }
  }

  protected function updateTags($tags, $item_profile)
  {
    try {
      $new_tags = array();
      $old_tags = array();

      // Organize new tags
      foreach ($tags as $key => $temp_tag) {
        array_push($new_tags, Str::lower($temp_tag));
      }

      // Organize old tags
      foreach ($item_profile->tags as $key => $temp_tag) {
        array_push($old_tags, $temp_tag->name);
      }

      // Compare tags
      $tag_intersect = array_intersect($new_tags, $old_tags);
      $tags_to_insert   = array_diff($new_tags, $tag_intersect);
      $tags_to_delete   = array_diff($old_tags, $tag_intersect);

      // Delete unused tags
      foreach ($tags_to_delete as $key => $tag_item) {
        $tag_slug = Str::slug($tag_item, '-');
        $tag = Tag::where('slug', $tag_slug)->get('id');

        $item_profile->tags()->detach($tag[0]->id);
      }

      // Insert new tags
      foreach ($tags_to_insert as $key => $tag_item) {
        $tag_name = Str::of($tag_item)->trim();
        $tag_slug = Str::slug($tag_name, '-');

        if (Tag::where('slug', $tag_slug)->exists()) {
          $tag = Tag::where('slug', $tag_slug)->get('id');

          $item_profile->tags()->attach($tag[0]->id);
        } else {
          $tag = new Tag;
          $tag->name = $tag_name;
          $tag->slug = $tag_slug;
          $tag->save();

          $item_profile->tags()->attach($tag->id);
        }
      }

      return 'Success';
    } catch (\Throwable $th) {
      return 'Error';
      //throw $th;
    }
  }
}

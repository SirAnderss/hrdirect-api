<?php

namespace App\Http\Traits;

use App\Social;
use Illuminate\Support\Str;

trait SocialManager
{

  protected function insertSocials($socials, $id)
  {
    try {
      foreach ($socials as $key => $social_item) {

        if (Social::where('link', $social_item)->exists()) {
          $social = Social::where('link', $social_item)->get();

          $social = Social::findOrFail($social[0]->id);

          $social->link = $social_item;
          $social->save();
        } else {
          $social_link = Str::of($social_item)->trim();
          $social = new Social;

          $social->link = Str::lower($social_link);
          $social->profile_id = $id;
          $social->save();
        }
      }

      return 'Success';
    } catch (\Throwable $th) {
      return 'Social Error';
      //throw $th;
    }
  }
}

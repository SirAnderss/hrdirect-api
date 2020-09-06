<?php

namespace App\Http\Traits;

use App\Social;

trait SocialManager
{

  protected function insertSocials($socials, $id)
  {
    try {
      foreach ($socials as $key => $social_item) {

        // if (Social::where('link', $social_item)->exists()) {
        //   $social = Social::where('link', $social_item)->get('id');
        // } else {
          $social = new Social;
          $social->link = $social_item;
          $social->profile_id = $id;
          $social->save();
        // }
      }

      return 'Success';
    } catch (\Throwable $th) {
      return 'Social Error';
      //throw $th;
    }
  }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{

  public $timestamps = false;

  /**
   * Get the picture_types that owns the picture.
   */
  public function picture_types()
  {
    return $this->belongsTo('App\Picture_type');
  }

  /**
   * Get the profile that owns the picture.
   */
  public function profile()
  {
    return $this->belongsTo('App\Profile');
  }
}

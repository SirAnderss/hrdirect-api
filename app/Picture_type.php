<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture_type extends Model
{

  public $timestamps = false;

  /**
   * Get the pictures for the picture type.
   */
  public function pictures()
  {
    return $this->hasMany('App\Picture');
  }
}

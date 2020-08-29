<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use SoftDeletes;

  /**
   * The profiles that belong to the category.
   */
  public function profiles()
  {
    return $this->belongsToMany('App\Profile');
  }
}

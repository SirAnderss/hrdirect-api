<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
  use SoftDeletes;

  protected $fillable = ['name'];

  /**
   * The profiles that belong to the tag.
   */
  public function profiles()
  {
    return $this->belongsToMany('App\Profile');
  }
}

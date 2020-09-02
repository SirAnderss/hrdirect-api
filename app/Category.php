<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use \App\Http\Traits\UsesUuid;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use SoftDeletes, UsesUuid;

  public $incrementing = false;

  protected $fillable = ['name', 'slug'];

  /**
   * The profiles that belong to the category.
   */
  public function profiles()
  {
    return $this->belongsToMany('App\Profile');
  }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Http\Traits\UsesUuid;

class Comment extends Model
{

  use UsesUuid;

  public $incrementing = false;

  /**
   * Get the profile that owns the comment.
   */
  public function profile()
  {
    return $this->belongsTo('App\Profile');
  }

  /**
   * Get the user that owns the comment.
   */
  public function user()
  {
    return $this->belongsTo('App\User');
  }
}

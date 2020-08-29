<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

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

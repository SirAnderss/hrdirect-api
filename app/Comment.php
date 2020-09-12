<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Http\Traits\UsesUuid;

class Comment extends Model
{

  use UsesUuid;

  public $incrementing = false;

  protected $fillable = ['comment', 'rating', 'avg_rating', 'profile_id', 'user_id'];

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

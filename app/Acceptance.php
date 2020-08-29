<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acceptance extends Model
{

  /**
   * Get the profile that owns the acceptance.
   */
  public function profile()
  {
    return $this->belongsTo('App\Profile');
  }

  /**
   * Get the user that owns the acceptance.
   */
  public function user()
  {
    return $this->belongsTo('App\User');
  }
}

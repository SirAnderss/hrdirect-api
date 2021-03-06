<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{

  public $timestamps = false;

  protected $fillable = ['link', 'profile_id'];

  /**
   * Get the profile that owns the social.
   */
  public function profile()
  {
    return $this->belongsTo('App\Profile');
  }
}

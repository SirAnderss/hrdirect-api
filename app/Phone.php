<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{

  public $timestamps = false;

  /**
   * Get the phone_types that owns the phone.
   */
  public function phone_types()
  {
    return $this->belongsTo('App\Phone_type');
  }

  /**
   * The profiles that belong to the phone.
   */
  public function profiles()
  {
    return $this->belongsToMany('App\Profile');
  }
}

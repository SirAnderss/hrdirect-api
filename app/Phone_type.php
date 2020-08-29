<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone_type extends Model
{

  public $timestamps = false;

  /**
   * Get the phones for the phone type.
   */
  public function phones()
  {
    return $this->hasMany('App\Phone');
  }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use \App\Http\Traits\UsesUuid;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
  use SoftDeletes, UsesUuid;

  protected $fillable = ['name', 'slug', 'address', 'description', 'user_id'];

  /**
   * Get the user that owns the profile.
   */
  public function user()
  {
    return $this->belongsTo('App\User');
  }

  /**
   * Get the socials for the profile.
   */
  public function socials()
  {
    return $this->hasMany('App\Social');
  }

  /**
   * Get the pictures for the profile.
   */
  public function pictures()
  {
    return $this->hasMany('App\Picture');
  }

  /**
   * Get the comments for the profile.
   */
  public function comments()
  {
    return $this->hasMany('App\Comment');
  }

  /**
   * The tags that belong to profile.
   */
  public function tags()
  {
    return $this->belongsToMany('App\Tag');
  }

  /**
   * The categories that belong to profile.
   */
  public function categories()
  {
    return $this->belongsToMany('App\Category');
  }

  /**
   * The phones that belong to profile.
   */
  public function phones()
  {
    return $this->belongsToMany('App\Phone');
  }
}

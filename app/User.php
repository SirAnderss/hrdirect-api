<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use \App\Http\Traits\UsesUuid;

class User extends Authenticatable implements JWTSubject
{
  use SoftDeletes;
  use Notifiable;
  use UsesUuid;

  public $incrementing = false;

  /**
   * Get the profiles for the user.
   */
  public function profiles()
  {
    return $this->hasMany('App\Profile');
  }

  /**
   * Get the acceptances for the user.
   */
  public function acceptances()
  {
    return $this->hasMany('App\Acceptance');
  }

  /**
   * Get the comments for the user.
   */
  public function comments()
  {
    return $this->hasMany('App\Comment');
  }

  /**
   * The roles that belong to the user.
   */
  public function roles()
  {
    return $this->belongsToMany('App\Role');
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  /**
   * @inheritDoc
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * @inheritDoc
   */
  public function getJWTCustomClaims()
  {
    return [];
  }

  public function setPasswordAttribute($value)
  {
    $this->attributes['password'] = Hash::make($value);
  }
}

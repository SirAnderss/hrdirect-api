<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\ApiCode;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login']]);
  }

  public function login()
  {
    $credentials = request()->validate(['email' => 'required|email', 'password' => 'required|string|max:25']);
    if (!$token = Auth::attempt($credentials)) {
      return $this->respondUnAuthorizedRequest(ApiCode::INVALID_CREDENTIALS);
    }

    return $this->respondWithToken($token);
  }

  private function respondWithToken($token)
  {
    return $this->respond([
      'token' => $token,
      'access_type' => 'bearer',
      'expires_in' => Auth::factory()->getTTL() * 60
    ], "Login Successful", ApiCode::OK);
  }


  public function logout()
  {
    Auth::logout();
    return $this->respondWithMessage(ApiCode::OK, 'User successfully logged out');
  }


  public function refresh()
  {
    return $this->respondWithToken(Auth::refresh(), null, ApiCode::OK,);
  }

  public function me()
  {
    $user = Auth::user();
    $roles = array();
    foreach ($user->roles as $role) {
      array_push($roles, $role->pivot->role_id);
    }

    return $this->respond($user, null, ApiCode::OK,);
  }
}

<?php

namespace App\Http\Controllers;

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
    if (!$token = auth()->attempt($credentials)) {
      return $this->respondUnAuthorizedRequest(ApiCode::INVALID_CREDENTIALS);
    }

    return $this->respondWithToken($token);
  }

  private function respondWithToken($token)
  {
    return $this->respond([
      'token' => $token,
      'access_type' => 'bearer',
      'expires_in' => auth()->factory()->getTTL() * 60
    ], "Login Successful", ApiCode::OK);
  }


  public function logout()
  {
    auth()->logout();
    return $this->respondWithMessage(ApiCode::OK, 'User successfully logged out');
  }


  public function refresh()
  {
    return $this->respondWithToken(auth()->refresh(), null, ApiCode::OK,);
  }

  public function me()
  {
    return $this->respond(auth()->user(), null, ApiCode::OK,);
  }
}

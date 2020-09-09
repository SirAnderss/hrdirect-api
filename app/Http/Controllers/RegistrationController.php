<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\User;
use App\ApiCode;

class RegistrationController extends Controller
{
  /**
   * Register User
   *
   * @param RegistrationRequest $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function register(RegistrationRequest $request)
  {
    if (User::where('email', $request->email)->exists()) {
      return $this->respondWithMessage(ApiCode::ALREADY_EXISTS, 'User already exists');
    } else {
      $user = new User;
      $user->name = $request->name;
      $user->email = $request->email;
      $user->password = $request->password;

      $user->save();
      $user->sendEmailVerificationNotification();

      return $this->respondWithMessage(ApiCode::OK, 'User successfully created');
    }
  }
}

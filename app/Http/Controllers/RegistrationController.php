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
    User::create($request->getAttributes())->sendEmailVerificationNotification();

    return $this->respondWithMessage(ApiCode::OK, 'User successfully created');
  }
}

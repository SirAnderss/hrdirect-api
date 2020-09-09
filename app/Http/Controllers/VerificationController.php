<?php

namespace App\Http\Controllers;

use App\ApiCode;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth:api')->except(['verify']);
  }

  /**
   * Verify email
   *
   * @param $user_id
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
   */
  public function verify($user_id, Request $request)
  {
    if (!$request->hasValidSignature()) {
      return $this->respondUnAuthorizedRequest(ApiCode::INVALID_EMAIL_VERIFICATION_URL);
    }

    $user = User::findOrFail($user_id);

    if (!$user->hasVerifiedEmail()) {
      $user->markEmailAsVerified();
    }

    $role = Role::where('name', 'REGISTERED')->get();

    $user->roles()->attach($role[0]->id);

    return redirect()->to('/');
  }

  /**
   * Resend email verification link
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function resend()
  {
    if (Auth::user()->hasVerifiedEmail()) {
      return $this->respondBadRequest(ApiCode::EMAIL_ALREADY_VERIFIED);
    }

    Auth::user()->sendEmailVerificationNotification();

    return $this->respondWithMessage(ApiCode::OK, "Email verification link sent on your email id");
  }
}

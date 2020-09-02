<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\ApiCode;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  public function updateProfile()
  {
    $attributes = request()->validate(['name' => 'nullable|string']);

    Auth::update($attributes);

    return $this->respondWithMessage(ApiCode::OK, "User successfully updated");
  }

  public function getUsers()
  {
    $users = User::paginate(5);
    return $this->respond($users, null, ApiCode::OK);
  }
}

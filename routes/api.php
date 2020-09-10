<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['api'])->group(function ($router) {
  // Route::group(['v1'], function () {
  Route::post('login', 'AuthController@login');
  Route::post('logout', 'AuthController@logout');
  Route::post('refresh', 'AuthController@refresh');
  Route::get('me', 'AuthController@me')->middleware('log.route');

  Route::post('register', 'RegistrationController@register');
  Route::get('email/verify/{id}', 'VerificationController@verify')->name('verification.verify');
  Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');
  Route::post('password/email', 'ForgotPasswordController@forgot');
  Route::post('password/reset', 'ForgotPasswordController@reset');

  Route::patch('user/profile', 'UserController@updateProfile');
  Route::get('users', 'UserController@getUsers');

  Route::resource('images', 'PictureController@store')->only([
    'store'
  ]);;

  Route::resource('profiles', 'ProfileController')->except([
    'create', 'edit'
  ]);

  Route::resource('categories', 'CategoryController')->only([
    'index', 'show'
  ]);

  Route::resource('tags', 'TagController')->only([
    'index', 'show'
  ]);

  Route::get('comments/{slug}', 'CommentController@index');
  Route::post('comments/{slug}', 'CommentController@store');

  // Route::resource('comments', 'CommentController')->except([
  //   'index', 'create', 'edit', 'store'
  // ]);
  // });
});

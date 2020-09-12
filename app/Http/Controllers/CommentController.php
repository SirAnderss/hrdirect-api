<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Profile;
use Illuminate\Support\Facades\Auth;
use  App\Http\Requests\CommentRequest;
use \App\Http\Traits\CommentManager;

use App\ApiCode;

class CommentController extends Controller
{

  use CommentManager;

  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store($slug, CommentRequest $request)
  {
    try {
      $profile = Profile::where('slug', $slug)->get('id');

      if (!Comment::where('user_id', Auth::user()->id)->exists()) {
        $comment = new Comment;
        // $comment->comment = $request->comment;
        $comment->rating = $request->rating;
        $comment->profile_id = $profile[0]->id;
        $comment->user_id = Auth::user()->id;
        $comment->save();

        $avg = $this->calcAvg($profile[0]->id, $request->rating);

        $comment = Comment::find($comment->id);
        $comment->avg_rating = $avg;
        $comment->save();

        return $this->respondWithMessage(ApiCode::OK, 'Calification saved succesfully');
      } else {
        return $this->respondWithMessage(ApiCode::ALREADY_EXISTS, 'User can´t qualify again');
      }
    } catch (\Throwable $th) {
      return $this->respondBadRequest(ApiCode::BAD_REQUEST);
      // throw $th;
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Comment  $comment
   * @return \Illuminate\Http\Response
   */
  public function update($id, CommentRequest $request)
  {
    $comment = Comment::findOrFail($id);

    $comment->$request->rating;
    $comment->save();

    $avg = $this->calcAvg($comment[0]->profile_id, $request->rating);

    $comment = Comment::find($comment->id);
    $comment->avg_rating = $avg;
    $comment->save();

    return $this->$this->respondWithMessage(ApiCode::OK, 'Calification updated succesfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Comment  $comment
   * @return \Illuminate\Http\Response
   */
  public function destroy($id, CommentRequest $request)
  {
    $comment = Comment::findOrFail($id);

    $comment->delete();

    return $this->respond(null, "Comment removed ", ApiCode::NO_CONTENT);
  }
}

<?php

namespace App\Http\Controllers;

use App\Comment;
use  App\Http\Requests\CommentRequest;
use \App\Http\Traits\CommentManager;

use App\ApiCode;

class CommentController extends Controller
{

  use CommentManager;

  // public function __construct()
  // {
  //   $this->middleware('auth:api', ['except' => ['index', 'list', 'show']]);
  // }

  /**
   * List all califications of current profile.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($slug)
  {
    // try {
    //   $profile = Profile::where('slug', $slug)->get('id');

    //   $avg = Comment::where('profile_id', $profile[0]->id)->orderBy('comments.updated_at', 'desc')->limit(1)->get('avg_rating');

    //   $star[1] = Comment::where('profile_id', $profile[0]->id)->where('rating', 1)->count();
    //   $star[2] = Comment::where('profile_id', $profile[0]->id)->where('rating', 2)->count();
    //   $star[3] = Comment::where('profile_id', $profile[0]->id)->where('rating', 3)->count();
    //   $star[4] = Comment::where('profile_id', $profile[0]->id)->where('rating', 4)->count();
    //   $star[5] = Comment::where('profile_id', $profile[0]->id)->where('rating', 5)->count();

    //   $comments = Comment::join('users', 'users.id', '=', 'comments.user_id')
    //     ->select(
    //       'comments.id',
    //       'comments.comment',
    //       'comments.rating',
    //       'comments.profile_id',
    //       'comments.user_id',
    //       'users.name as username'
    //     )
    //     ->where('profile_id', $profile[0]->id)
    //     ->orderBy('comments.updated_at', 'desc')
    //     ->paginate(10);

    //   if (!empty($comments)) {
    //     return $this->respond([
    //       'star_1' => $star[1],
    //       'star_2' => $star[2],
    //       'star_3' => $star[3],
    //       'star_4' => $star[4],
    //       'star_5' => $star[5],
    //       'avg' => $avg[0]->avg_rating,
    //       'comments' => $comments
    //     ], 'Comments listed successfully', ApiCode::OK);
    //   } else {
    //     return $this->respondWithMessage(ApiCode::NO_CONTENT, 'No comments found');
    //   }
    // } catch (\Throwable $th) {
    //   return $this->respondBadRequest(ApiCode::BAD_REQUEST);
    //   // throw $th;
    // }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store($slug, CommentRequest $request)
  {
    dd($slug);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Comment  $comment
   * @return \Illuminate\Http\Response
   */
  public function show(Comment $comment)
  {
    //
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Comment  $comment
   * @return \Illuminate\Http\Response
   */
  public function update(CommentRequest $request, Comment $comment)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Comment  $comment
   * @return \Illuminate\Http\Response
   */
  public function destroy($id/*, CommentRequest $request*/)
  {
    $comment = Comment::findOrFail($id);

    $comment->delete();

    return $this->respond(null, "Comment removed ", ApiCode::NO_CONTENT);
  }
}

<?php

namespace App\Http\Traits;

use \App\Comment;
use \App\Profile;

use App\ApiCode;

trait CommentManager
{

  /**
   * List all comments, rating and avg of current profile.
   *
   * @return \Illuminate\Http\Response
   */
  public function getComments($id)
  {
    try {

      $avg = Comment::where('profile_id', $id)->orderBy('comments.updated_at', 'desc')->limit(1)->get('avg_rating');

      $star[1] = Comment::where('profile_id', $id)->where('rating', 1)->count();
      $star[2] = Comment::where('profile_id', $id)->where('rating', 2)->count();
      $star[3] = Comment::where('profile_id', $id)->where('rating', 3)->count();
      $star[4] = Comment::where('profile_id', $id)->where('rating', 4)->count();
      $star[5] = Comment::where('profile_id', $id)->where('rating', 5)->count();

      // $comments = Comment::join('users', 'users.id', '=', 'comments.user_id')
      //   ->select(
      //     'comments.id',
      //     'comments.comment',
      //     'comments.rating',
      //     'comments.user_id',
      //     'users.name as username'
      //   )
      //   ->where('profile_id', $id)
      //   ->orderBy('comments.updated_at', 'desc')
      //   ->paginate(10);

      if (!empty($avg)) {
        return [
          'star_1' => $star[1],
          'star_2' => $star[2],
          'star_3' => $star[3],
          'star_4' => $star[4],
          'star_5' => $star[5],
          'avg' => $avg[0]->avg_rating,
          // 'comments' => $comments
        ];
      } else {
        return 'No califications found';
      }
    } catch (\Throwable $th) {
      return $this->respondBadRequest(ApiCode::BAD_REQUEST); // Revisar
      // throw $th;
    }
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected function calcAvg($id, $user_rating)
  {

    $rating = Comment::where('profile_id', $id)->get('rating');

    if (count($rating) == 0) {
      $avg = $user_rating;

      return $avg;
    } else {
      $temp = $user_rating;
      $count = count($rating);

      foreach ($rating as $key => $value) {
        $temp = $temp + $value->rating;
      }

      $avg = $temp / ($count);

      return $avg;
    }
  }
}

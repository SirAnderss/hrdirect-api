<?php

namespace App\Http\Controllers;

use App\Picture;
use Illuminate\Http\Request;
use \App\Http\Traits\FileUpload;
use App\ApiCode;

class PictureController extends Controller
{

  use FileUpload;

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
  public function store(Request $request)
  {
    $avatar = $request->avatar;
    $cover = $request->cover;
    $content = $request->content;

    if (!empty($avatar) && empty($cover) && empty($content)) {
      $type = 1;
    } else if (empty($avatar) && !empty($cover) && empty($content)) {
      $type = 2;
    } else if (empty($avatar) && empty($cover) && !empty($content)) {
      $type = 3;
    } else {
      $type = null;
    }

    switch ($type) {
      case '1':
        $img_name = $this->uploadImage($avatar, $type);

        return $this->respond($img_name, null, ApiCode::OK);
        break;
      case '2':
        $img_name = $this->uploadImage($cover, $type);

        return $this->respond($img_name, null, ApiCode::OK);
        break;
      case '3':
        $img_name = $this->uploadImage($content, $type);

        return $this->respond($img_name, null, ApiCode::OK);
        break;
      default:
        return $this->respondWithError(ApiCode::BAD_REQUEST, 400);
        break;
    }

    return '';
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Picture  $pictures
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Picture $pictures)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Picture  $pictures
   * @return \Illuminate\Http\Response
   */
  public function destroy(Picture $pictures)
  {
    //
  }
}

<?php

namespace App\Http\Controllers;

use App\Category;
use App\Picture;
use Illuminate\Http\Request;
use \App\Http\Traits\PaginateHelper;

use App\ApiCode;

class CategoryController extends Controller
{

  use PaginateHelper;

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    try {
      $categories = Category::select(
        'id',
        'name',
        'slug',
      )
        ->paginate(10);

      return $this->respond($categories, 'Categories listed successfully', ApiCode::OK);
    } catch (\Throwable $th) {
      return $this->respondBadRequest(ApiCode::BAD_REQUEST);
      // throw $th;
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function show($slug)
  {
    try {
      $category = Category::where('slug', $slug)->get();

      $category = Category::find($category[0]->id);

      $profiles = array();

      foreach ($category->profiles as $profile) {
        $thumb = Picture::select('picture_link')->where('profile_id', $profile->id)->get();

        $category_item = ['thumb' => $thumb[0]->picture_link, 'profile_name' => $profile->name, 'slug' => $profile->slug];

        array_push($profiles, $category_item);
      }

      if (!empty($profiles)) {
        $data = $this->paginate($profiles, 10);

        return $this->respond($data, 'Get profiles by categories successfully', ApiCode::OK);
      } else {
        return $this->respondWithMessage(ApiCode::NO_CONTENT, 'No profiles found');
      }
    } catch (\Throwable $th) {
      return $this->respondBadRequest(ApiCode::BAD_REQUEST);
      // throw $th;
    }
  }
}

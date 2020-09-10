<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Picture;
use Illuminate\Http\Request;
use \App\Http\Traits\PaginateHelper;

use App\ApiCode;

class TagController extends Controller
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
      $categories = Tag::select(
        'id',
        'name',
        'slug',
      )
        ->paginate(10);

      return $this->respond($categories, 'Tags listed successfully', ApiCode::OK);
    } catch (\Throwable $th) {
      return $this->respondBadRequest(ApiCode::BAD_REQUEST);
      //throw $th;
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Tag  $tags
   * @return \Illuminate\Http\Response
   */
  public function show($slug)
  {
    try {
      $category = Tag::where('slug', $slug)->get();

      $category = Tag::find($category[0]->id);

      $profiles = [];

      foreach ($category->profiles as $profile) {
        $thumb = Picture::select('picture_link')->where('profile_id', $profile->id)->get();

        $category_item = ['thumb' => $thumb[0]->picture_link, 'profile_name' => $profile->name, 'slug' => $profile->slug];

        array_push($profiles, $category_item);
      }

      if (!empty($profiles)) {
        $data = $this->paginate($profiles, 10);

        return $this->respond($data, 'Get profiles by tags successfully', ApiCode::OK);
      } else {
        return $this->respondWithMessage(ApiCode::NO_CONTENT, 'No profiles found');
      }
    } catch (\Throwable $th) {
      return $this->respondBadRequest(ApiCode::BAD_REQUEST);
      // throw $th;
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Tag  $tags
   * @return \Illuminate\Http\Response
   */
  public function edit(Tag $tags)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Tag  $tags
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Tag $tags)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Tag  $tags
   * @return \Illuminate\Http\Response
   */
  public function destroy(Tag $tags)
  {
    //
  }
}

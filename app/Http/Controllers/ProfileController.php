<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Picture;
use App\Category;
use App\Tag;
use App\Phone;
use App\Comment;
use App\Acceptance;
use App\Social;

use App\ApiCode;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Http\Requests\ProfileRequest;

use \App\Http\Traits\FileUpload;
use \App\Http\Traits\TagsManager;
use \App\Http\Traits\CategoriesManager;
use \App\Http\Traits\PhoneManager;
use \App\Http\Traits\SocialManager;
use \App\Http\Traits\CommentManager;

class ProfileController extends Controller
{
  use FileUpload, TagsManager, CategoriesManager, PhoneManager, SocialManager, CommentManager;

  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['index', 'show']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    try {
      $profiles = Picture::join('profiles', 'profiles.id', '=', 'pictures.profile_id')
        ->join('picture_types', 'picture_types.id', '=', 'pictures.picture_type_id')
        ->select(
          'profiles.id as id',
          'profiles.name as name',
          'profiles.slug as slug',
          'profiles.address as address',
          'profiles.description as description',
          'pictures.picture_link as avatar',
        )
        ->where('picture_types.id', 5)
        ->paginate(10);

      return $this->respond($profiles, 'Profiles listed successfully', ApiCode::OK);
    } catch (\Throwable $th) {
      return $this->respondBadRequest(ApiCode::BAD_REQUEST); //Chekar esto
      // throw $th;
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ProfileRequest $request)
  {
    try {
      $profile = $this->addProfile($request);

      $new_profile = $profile[1];
      $id = $profile[0];

      if ($new_profile == 'Success') {

        /* Images trait
        * This section handle all the images of the new profile
        */

        // Image profile
        if (!empty($request->avatar_name)) {
          $this->storeFileName($request->avatar_thumb_name, 5, $id);
          $avatar = $this->storeFileName($request->avatar_name, 1, $id);
        } else {
          $avatar = $this->staticProfile($id);
        }

        // Cover profile image
        if (!empty($request->cover_name)) {
          $this->storeFileName($request->cover_thumb_name, 4, $id);
          $cover = $this->storeFileName($request->cover_name, 2, $id);
        } else {
          $cover = 'Empty';
        }

        // Content images
        if (!empty($request->content_files_name)) {
          foreach ($request->content_files_name as $key => $content_file) {
            $content = $this->storeFileName($content_file, 3, $id);
          }

          foreach ($request->content_thumbs_name as $key => $thumb_file) {
            $this->storeFileName($thumb_file, 4, $id);
          }
        } else {
          $content = 'Empty';
        }

        $item_profile = Profile::find($id);

        // Socials trait
        if (!empty($request->socials)) {
          $socials = $request->socials;
          $new_socials = $this->insertSocials($socials, $id);
        } else {
          $new_socials = 'Empty';
        }

        // Tags trait
        $tags = $request->tags;
        $new_tags = $this->insertTags($tags, $item_profile);

        // Categories trait
        $categories = $request->categories;
        $new_categories = $this->insertCategories($categories, $item_profile);

        // Phones trait
        if (!empty($request->phones)) {
          $phones = $request->phones;
          $new_phones = $this->insertPhones($phones, $item_profile);
        } else {
          $new_phones = 'Empty';
        }

        return $this->respond([
          'profile' => $new_profile . ' profile',
          'cover' => $cover . ' cover',
          'avatar' => $avatar . ' avatar',
          'content_files' => $content . ' content',
          'tags' => $new_tags . ' tags',
          'categores' => $new_categories . ' categories',
          'phones' => $new_phones . ' phones',
          'socials' => $new_socials . ' socials'
        ], "Profile created ", ApiCode::OK);
      } else {
        return $this->respondWithMessage(ApiCode::ALREADY_EXISTS, 'Profile already exists');
      }
    } catch (\Throwable $th) {
      return $this->respondBadRequest(ApiCode::BAD_REQUEST);
      //throw $th;
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Profile  $profile
   * @return \Illuminate\Http\Response
   */
  public function show($slug)
  {
    try {
      $profile = Profile::where('slug', $slug)->get('id');

      $id = $profile[0]->id;
      $info_profile = Profile::select(
        'profiles.id as id',
        'profiles.name as name',
        'profiles.slug as slug',
        'profiles.address as address'
      )
        ->where('profiles.id', $id)
        ->get();

      $pictures = Picture::select('picture_link', 'picture_type_id as picture_type')->where('profile_id', $id)->get();

      $comments = $this->getComments($id);

      $socials = Social::select('link')->where('profile_id', $id)->get();

      $categories = array();
      $tags = array();
      $phones = array();

      $profile = Profile::find($id);

      foreach ($profile->categories as $category) {
        $category_item = $category->pivot->category_id;
        $category_name = Category::select('name')->where('id', $category_item)->get();
        array_push($categories, $category_name[0]);
      }

      foreach ($profile->tags as $tag) {
        $tag_item = $tag->pivot->tag_id;
        $tag_name = Tag::select('name')->where('id', $tag_item)->get();
        array_push($tags, $tag_name[0]);
      }

      foreach ($profile->phones as $phone) {
        $phone_item = $phone->pivot->phone_id;
        $phone_number = Phone::join('phone_types', 'phone_types.id', '=', 'phones.phone_type_id')
          ->select('phone_number as number', 'phone_types.name as type')
          ->where('phones.id', $phone_item)
          ->get();
        array_push($phones, $phone_number[0]);
      }

      return $this->respond([
        'profile' => $info_profile,
        'categories' => $categories,
        'tags' => $tags,
        'phones' => $phones,
        'pictures' => $pictures,
        'socials' => $socials,
        'comments' => $comments
      ], 'Get profile successfully', ApiCode::OK);
    } catch (\Throwable $th) {
      return $this->respondBadRequest(ApiCode::BAD_REQUEST);
      // throw $th;
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Profile  $profile
   * @return \Illuminate\Http\Response
   */
  public function update($id, ProfileRequest $request)
  {
    try {
      $profile = $this->updateProfile($request, $id);

      $new_profile = $profile;

      if ($new_profile == 'Success') {

        /* Images trait
        * This section handle all the images of the new profile
        */

        // Image profile
        if (!empty($request->avatar_name)) {
          $avatar = Picture::where('profile_id', $id)->where('picture_type_id', 1)->get();

          $this->deleteImage('avatar', $avatar[0]->picture_link);

          $avatar->picture_link = $request->avatar_name;
          $avatar->save();

          $thumb = Picture::where('profile_id', $id)->where('picture_type_id', 1)->get();
          $thumb->picture_link = $request->avatar_thumb_name;
          $thumb->save();

          $avatar = 'Updated';
        } else {
          $avatar = 'Nothing to Update';
        }

        // Cover profile image
        if (!empty($request->cover_name)) {
          $cover = Picture::where('profile_id', $id)->where('picture_type_id', 1)->get();

          $this->deleteImage('cover', $cover[0]->picture_link);

          $cover->picture_link = $request->cover_name;
          $cover->save();

          $thumb = Picture::where('profile_id', $id)->where('picture_type_id', 1)->get();
          $thumb->picture_link = $request->cover_thumb_name;
          $thumb->save();

          $cover = 'Updated';
        } else {
          $cover = 'Nothing to Update';
        }

        // Content images
        if (!empty($request->content_files_name)) {
          $this->contentImage(3, $request->content_thumbs_name, $id);
          $content = $this->contentImage(3, $request->content_files_name, $id);
        } else {
          $content = 'Nothing to Update';
        }

        $item_profile = Profile::find($id);

        // Socials trait
        if (!empty($request->socials)) {
          $socials = $request->socials;
          $new_socials = $this->insertSocials($socials, $id);
        } else {
          $new_socials = 'Nothing to Update';
        }

        // Tags trait
        $tags = $request->tags;
        $new_tags = $this->updateTags($tags, $item_profile);

        // Categories trait
        $categories = $request->categories;
        $new_categories = $this->updateCategories($categories, $item_profile);

        // Phones trait
        if (!empty($request->phones)) {
          $phones = $request->phones;
          $new_phones = $this->updatePhones($phones, $item_profile);
        } else {
          $new_phones = 'Nothing to Update';
        }
      }

      return $this->respond([
        'profile' => $new_profile . ' in profile',
        'cover' => $cover . ' in cover',
        'avatar' => $avatar . ' in avatar',
        'content_files' => $content . ' in content',
        'tags' => $new_tags . ' in tags',
        'categores' => $new_categories . ' in categories',
        'phones' => $new_phones . ' in phones',
        'socials' => $new_socials . ' in socials'
      ], "Profile updated ", ApiCode::OK);
    } catch (\Throwable $th) {
      return $this->respondBadRequest(ApiCode::BAD_REQUEST);
      //throw $th;
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Profile  $profile
   * @return \Illuminate\Http\Response
   */
  public function destroy($id/*, CommentRequest $request*/)
  {
    $profile = Profile::findOrFail($id);

    $profile->delete();

    return $this->respond(null, "Profile removed ", ApiCode::NO_CONTENT);
  }

  /**
   * Add profile info to database
   *
   * @param  \App\Profile  $profile
   * @return \Illuminate\Http\Response
   */
  private function addProfile($data)
  {
    try {
      $name = trim($data->name);
      $slug = Str::slug($name, '-');
      if (Profile::where('slug', $slug)->exists()) {
        $id = null;
        $new_profile = 'Not available';

        return [$id, $new_profile];
      } else {
        $profile = new Profile;
        $profile->name = $name;
        $profile->slug = $slug;
        $profile->address = $data->address;
        $profile->description = $data->description;
        $profile->user_id = Auth::user()->id;
        $profile->save();

        $id = $profile->id;
        $new_profile = 'Success';

        return [$id, $new_profile];
      }
    } catch (\Throwable $th) {
      $new_profile = 'Error';
      return $new_profile;
      //throw $th;
    }
  }

  /**
   * Update profile info to database
   *
   * @param  \App\Profile  $profile
   * @return \Illuminate\Http\Response
   */
  private function updateProfile($data, $id)
  {
    try {
      $name = trim($data->name);

      $profile = Profile::findOrFail($id);

      $profile->name = $name;
      $profile->address = $data->address;
      $profile->description = $data->description;
      $profile->save();

      $new_profile = 'Success';

      return $new_profile;
    } catch (\Throwable $th) {
      $new_profile = 'Error';
      return $new_profile;
      //throw $th;
    }
  }
}

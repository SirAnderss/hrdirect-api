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

class ProfileController extends Controller
{

  use FileUpload, TagsManager, CategoriesManager, PhoneManager, SocialManager;

  public function __construct()
  {
    $this->middleware('auth:api'/*, ['except' => ['index', 'show']]*/);
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
      return $this->respondServerInternalError(ApiCode::INTERNAL_SERVER_ERROR);
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
        if (!empty($request->image_profile)) {
          $avatar = $this->uploadImage($request->image_profile, 1, $id);
        } else {
          $avatar = $this->staticProfile($id);
        }

        // Cover profile image
        if (!empty($request->cover_profile)) {
          $cover = $this->uploadImage($request->cover_profile, 2, $id);
        } else {
          $cover = 'Empty';
        }

        // Content images
        if (!empty($request->content_file)) {
          foreach ($request->content_file as $key => $content_file) {
            $content = $this->uploadImage($content_file, 3, $id);
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
          'cover_profile' => $cover . ' cover',
          'image_profile' => $avatar . ' avatar',
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
      return $this->respondServerInternalError(ApiCode::INTERNAL_SERVER_ERROR);
      //throw $th;
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Profile  $profile
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    try {
      $info_profile = Profile::select(
        'profiles.id as id',
        'profiles.name as name',
        'profiles.slug as slug',
        'profiles.address as address'
      )
        ->where('profiles.id', $id)
        ->get();

      $pictures = Picture::select('picture_link', 'picture_type_id as picture_type')->where('profile_id', $id)->get();

      $acceptances = Acceptance::join('users', 'users.id', '=', 'acceptances.user_id')
        ->select(
          'acceptances.rating as rating',
          'users.name as username'
        )
        ->where('acceptances.profile_id', $id)
        ->get();

      $comments = Comment::join('users', 'users.id', '=', 'comments.user_id')
        ->select(
          'comments.comment as comment',
          'users.name as username'
        )
        ->where('comments.profile_id', $id)
        ->get();

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
        'acceptances' => $acceptances,
        'comments' => $comments
      ], 'Get profile successfully', ApiCode::OK);
    } catch (\Throwable $th) {
      return $th;
      // return $this->respondServerInternalError(ApiCode::INTERNAL_SERVER_ERROR);
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
        if (!empty($request->image_profile)) {
          $old_avatar = Picture::where('profile_id', $id)->where('picture_type_id', 1);

          $avatar = $this->uploadImage($request->image_profile, 1, $id);
          $this->deleteImage('avatar', $old_avatar[0]->picture_link);

        } else {
          $avatar = 'Nothing to Update';
        }

        // Cover profile image
        if (!empty($request->cover_profile)) {
          $old_cover = Picture::where('profile_id', $id)->where('picture_type_id', 2);
          $cover = $this->uploadImage($request->cover_profile, 2, $id);
          $this->deleteImage('cover', $old_cover[0]->picture_link);
        } else {
          $cover = 'Nothing to Update';
        }

        // Content images
        if (!empty($request->content_file)) {
          foreach ($request->content_file as $key => $content_file) {
            $old_content = Picture::where('profile_id', $id)->where('picture_type_id', 3);

            $content = $this->uploadImage($content_file, 3, $id);
            $this->deleteImage('content', $old_content[0]->picture_link);
          }
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
        'cover_profile' => $cover . ' in cover',
        'image_profile' => $avatar . ' in avatar',
        'content_files' => $content . ' in content',
        'tags' => $new_tags . ' in tags',
        'categores' => $new_categories . ' in categories',
        'phones' => $new_phones . ' in phones',
        'socials' => $new_socials . ' in socials'
      ], "Profile created ", ApiCode::OK);
    } catch (\Throwable $th) {
      return $th/*$this->respondServerInternalError(ApiCode::INTERNAL_SERVER_ERROR)*/;
      //throw $th;
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Profile  $profile
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
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

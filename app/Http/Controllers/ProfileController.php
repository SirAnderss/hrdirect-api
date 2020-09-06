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
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    // Debo dejar una imagen por defecto para la imagen de perfil
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
  public function show(Profile $profile)
  {
    try {
      $info_profile = Profile::select(
        'profiles.id as id',
        'profiles.name as name',
        'profiles.slug as slug',
        'profiles.address as address'
      )
        ->where('profiles.id', $profile->id)
        ->get();

      $pictures = Picture::select('picture_link')->where('profile_id', $info_profile[0]->id)->get();

      $acceptances = Acceptance::join('users', 'users.id', '=', 'acceptances.user_id')
        ->select(
          'acceptances.rating as rating',
          'users.name as username'
        )
        ->where('acceptances.profile_id', $info_profile[0]->id)
        ->get();

      $comments = Comment::join('users', 'users.id', '=', 'comments.user_id')
        ->select(
          'comments.comment as comment',
          'users.name as username'
        )
        ->where('comments.profile_id', $info_profile[0]->id)
        ->get();

      $socials = Social::select('link')->where('profile_id', $info_profile[0]->id)->get();

      $categories = array();
      $tags = array();
      $phones = array();

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
          ->select('phone_number as number', 'phone_types.name as phone_type')
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
      return $this->respondServerInternalError(ApiCode::INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Profile  $profile
   * @return \Illuminate\Http\Response
   */
  public function edit(Profile $profile)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Profile  $profile
   * @return \Illuminate\Http\Response
   */
  public function update(ProfileRequest $request, Profile $profile)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Profile  $profile
   * @return \Illuminate\Http\Response
   */
  public function destroy(Profile $profile)
  {
    //
  }

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
      $new_profile = 'Error Profile';
      return $new_profile;
      //throw $th;
    }
  }
}

<?php

namespace App\Http\Traits;

use App\Picture;
use Image;
use File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUpload
{
  private $images_path = '';
  private $thumbnail_path = '';
  private $avatar_path = '';
  private $cover_path = '';

  /**
   * create required directory if not exist and set permissions
   */
  private function createDirecrotory()
  {
    $paths = [
      'image_path' => public_path('storage/images/'),
      'thumbnail_path' => public_path('storage/images/thumbs/'),
      'avatar_path' => public_path('storage/images/avatars/'),
      'cover_path' => public_path('storage/images/covers/'),
    ];

    foreach ($paths as $key => $path) {
      if (!File::isDirectory($path)) {
        File::makeDirectory($path, 0777, true, true);
      }
    }

    $this->images_path = $paths['image_path'];
    $this->thumbnail_path = $paths['thumbnail_path'];
    $this->avatar_path = $paths['avatar_path'];
    $this->cover_path = $paths['cover_path'];
  }

  /**
   * create thumbs for the images
   */
  private function createThumb($user_file)
  {
    $temp_image = Image::make($user_file);
    // resize and save thumbnail
    $temp_image->resize(400, null, function ($constraint) {
      $constraint->aspectRatio();
      $constraint->upsize();
    });

    $temp_image->orientate();
    $thumb_name = Str::uuid() . '.webp';

    $temp_image->save($this->thumbnail_path . $thumb_name);

    return $thumb_name;
  }

  /**
   * store image filename in the database
   */
  private function storeFileName($file_name, $type, $id)
  {

    Picture::create(['picture_link' => $file_name, 'picture_type_id' => $type, 'profile_id' => $id]);
  }

  /**
   * Upload Image to storage and save on Db
   */
  public function uploadImage($user_file, $type, $id)
  {

    $this->createDirecrotory();

    try {

      $image = Image::make($user_file);
      $image->encode('webp');

      // image rename
      $image_name = Str::uuid() . '.webp';

      // save original image
      switch ($type) {
        case '1':
          $image->save($this->avatar_path . $image_name);

          $this->storeFileName($image_name, 1, $id);

          $thumb_name = $this->createThumb($image);

          $this->storeFileName($thumb_name, 5, $id);
          break;

        case '2':
          $image->save($this->cover_path . $image_name);

          $this->storeFileName($image_name, 2, $id);

          $thumb_name = $this->createThumb($image);

          $this->storeFileName($thumb_name, 4, $id);
          break;

        case '3':
          $image->save($this->images_path . $image_name);

          $this->storeFileName($image_name, 2, $id);

          $thumb_name = $this->createThumb($image);

          $this->storeFileName($thumb_name, 4, $id);
          break;

        default:
          break;
      }

      return 'Success';
    } catch (\Throwable $th) {
      return 'error';
    }
  }

  /**
   * Set default avatar
   */
  protected function staticProfile($profile_id)
  {
    try {
      Picture::create(['picture_link' => 'avatar.webp', 'picture_type_id' => 1, 'profile_id' => $profile_id]);
      Picture::create(['picture_link' => 'thumb_avatar.webp', 'picture_type_id' => 5, 'profile_id' => $profile_id]);

      return 'Success';
    } catch (\Throwable $th) {
      return 'Error';
      //throw $th;
    }
  }

  /**
   * Set default avatar
   */
  protected function deleteImage($type, $name)
  {
    try {
      switch ($type) {
        case 'avatar':
          if ($name != 'avatar.webp') {
            Storage::delete($this->avatar_path . $name);
          }
          break;
        case 'cover':
          Storage::delete($this->cover_path . $name);
          break;
        case 'content':
          Storage::delete($this->images_path . $name);
          break;
        case 'thumbs':
          Storage::delete($this->thumbnail_path . $name);
          break;

        default:
          break;
      }
    } catch (\Throwable $th) {
      return 'Error';
      //throw $th;
    }
  }
}

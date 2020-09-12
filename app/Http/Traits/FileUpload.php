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

    Picture::create([
      'picture_link' => url('storage/images/') . '/' . $file_name,
      'picture_type_id' => $type,
      'profile_id' => $id
    ]);

    return 'Success';
  }

  /**
   * Upload Image to storage and save on Db
   */
  public function uploadImage($user_file, $type)
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

          $thumb_name = $this->createThumb($image);

          return ['img_name' => $image_name, 'thumb' => $thumb_name, 'type_img' => $type];
          break;

        case '2':
          $image->save($this->cover_path . $image_name);

          $thumb_name = $this->createThumb($image);

          return ['img_name' => $image_name, 'thumb' => $thumb_name, 'type_img' => $type];
          break;

        case '3':
          $image->save($this->images_path . $image_name);

          $thumb_name = $this->createThumb($image);

          return ['img_name' => $image_name, 'thumb' => $thumb_name, 'type_img' => $type];
          break;

        default:
          break;
      }
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
      Picture::create([
        'picture_link' =>  url('storage/images/') . '/' . 'avatar.webp',
        'picture_type_id' => 1,
        'profile_id' => $profile_id
      ]);

      Picture::create([
        'picture_link' =>  url('storage/images/') . '/' . 'thumb_avatar.webp',
        'picture_type_id' => 5,
        'profile_id' => $profile_id
      ]);

      return 'Success';
    } catch (\Throwable $th) {
      return 'Error';
      //throw $th;
    }
  }

  /**
   * Content images manager
   */
  protected function contentImage($type, $name, $id)
  {
    $old_files = array();
    $new_files = array();

    try {

      $old_content = Picture::where('profile_id', $id)->where('picture_type_id', 3)->get();

      foreach ($old_content as $key => $old_value) {
        array_push($old_files, $old_value->picture_link);
      };

      foreach ($name as $key => $content_file) {
        array_push($new_files, $content_file);
      }

      $files_intersect = array_intersect($new_files, $old_files);
      $files_to_insert = array_diff($new_files, $files_intersect);
      $files_to_delete = array_diff($old_files, $files_intersect);

      foreach ($files_to_insert as $key => $file_to_insert) {
        $file = new Picture;
        $file->picture_link = $file_to_insert;
        $file->picture_type_id = 3;
        $file->save();
      }

      switch ($type) {
        case '3':
          foreach ($files_to_delete as $key => $file_to_delete) {
            $file = Picture::where('picture_link', $file_to_delete)->where('picture_type_id', 3)->get();
            $destroy = Picture::find($file[0]->id);

            $this->deleteImage('content', $file[0]->picture_link);
            $destroy->delete();
          }
          break;
        case '4':
          foreach ($files_to_delete as $key => $file_to_delete) {
            $file = Picture::where('picture_link', $file_to_delete)->where('picture_type_id', 4)->get();
            $destroy = Picture::find($file[0]->id);

            $this->deleteImage('thumbs', $file[0]->picture_link);
            $destroy->delete();
          }
          break;

        default:
          break;
      }

      return 'Updated';
    } catch (\Throwable $th) {
      return 'Error';
      //throw $th;
    }
  }

  /**
   * Remove image from storage
   */
  protected function deleteImage($type, $name)
  {
    try {
      switch ($type) {
        case 'avatar':
          if ($name != 'avatar.webp') {
            if (Storage::disk('local')->exists('public/images/avatars/' . $name)) {
              Storage::delete($this->avatar_path . $name);
            }
          }
          break;
        case 'cover':
          if (Storage::disk('local')->exists('public/images/avatars/' . $name)) {
            Storage::delete($this->cover_path . $name);
          }
          break;
        case 'content':
          if (Storage::disk('local')->exists('public/images/avatars/' . $name)) {
            Storage::delete($this->images_path . $name);
          }
          break;
        case 'thumbs':
          if (Storage::disk('local')->exists('public/images/avatars/' . $name)) {
            Storage::delete($this->thumbnail_path . $name);
          }
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

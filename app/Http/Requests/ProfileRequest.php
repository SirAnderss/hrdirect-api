<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'name' => 'required|string|min:8|max:70|unique:profiles',
      'address' => 'required|string|min:8|max:100',
      'description' => 'required|string|min:8|max:700',
      'image_profile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
      'cover_profile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
      'content_file' => 'array|min:1',
      'content_file.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
      'tags' => 'required|array|min:1',
      'tags.*' => 'required|string|min:3|max:30',
      'categories' => 'required|array|min:1',
      'categories.*' => 'required|string|min:3|max:80',
      'socials' => 'array|min:1',
      'socials.*' => 'string|min:3|max:100',
      'phones' => 'array|min:1',
      'phones.*' => 'array|min:1',
      'phones.*.number' => 'integer|digits_between:5,13',
      'phones.*.type' => 'integer|between:1,4',
    ];
  }
}

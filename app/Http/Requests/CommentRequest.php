<?php

namespace App\Http\Requests;

use App\Comment;
use Illuminate\Support\Arr;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    // // Validar que el usuario sea el mismo que creo el item
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $contains = $this->validationData();
    $contains = Arr::hasAny($contains, ['comment', 'rating']);

    if ($contains) {
      return [
        'comment' => 'nullable|string|min:8|max:300',
        'rating' => 'nullable|integer|between:1,5'
      ];
    } else {
      return [
        'inputs' => 'required'
      ];
    }
  }
}

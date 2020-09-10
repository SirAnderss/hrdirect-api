<?php

namespace App\Http\Traits;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait PaginateHelper
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  public function paginate($items, $perPage, $page = null, $options = [])
  {
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
      'path' => Paginator::resolveCurrentPath(),
      'pageName' => 'page',
    ]);
  }
}

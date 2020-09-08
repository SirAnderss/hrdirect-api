<?php

namespace App\Http\Traits;

use App\Phone;

trait PhoneManager
{
  private function insertPhones($phones, $item_profile)
  {
    try {
      $phones = json_encode($phones);
      $phones = json_decode($phones);

      foreach ($phones as $key => $phone_item) {
        if (Phone::where('phone_number', $phone_item->number)->exists()) {
          $phone = Phone::where('phone_number', $phone_item->number)->get('id');

          $item_profile->phones()->attach($phone[0]->id);
        } else {
          $phone = new Phone;
          $phone->phone_number = $phone_item->number;
          $phone->phone_type_id = $phone_item->type;
          $phone->save();

          $item_profile->phones()->attach($phone->id);
        }
      }

      return 'Success';
    } catch (\Throwable $th) {
      return 'Error';
      //throw $th;
    }
  }

  private function updatePhones($new_phones, $item_profile)
  {
    try {
      // Get old phones
      $old_phones = array();

      foreach ($item_profile->phones as $phone) {
        $phone_item = $phone->pivot->phone_id;

        $phone_number = Phone::join('phone_types', 'phone_types.id', '=', 'phones.phone_type_id')
          ->select('phone_number as number', 'phone_types.id as type')
          ->where('phones.id', $phone_item)
          ->get();
        array_push($old_phones, $phone_number[0]);
      }

      // Organize new phones object
      $new_phones = json_encode($new_phones);
      $new_phones = json_decode($new_phones);

      // Get phones to insert
      $phones_to_insert = array_udiff(
        $new_phones,
        $old_phones,
        function ($obj_a, $obj_b) {
          return $obj_a->number - $obj_b->number;
        }
      );

      // Get phones to delete
      $phones_to_delete = array_udiff(
        $old_phones,
        $new_phones,
        function ($obj_b, $obj_a) {
          return $obj_b->number - $obj_a->number;
        }
      );

      // Delete unused phones
      foreach ($phones_to_delete as $key => $phone_item) {
        $phone = Phone::where('phone_number', $phone_item->number)->get('id');

        $item_profile->phones()->detach($phone[0]->id);
        $phone = Phone::findOrFail($phone[0]->id);
        $phone->delete();
      }

      // Insert new phones
      foreach ($phones_to_insert as $key => $phone_item) {
        if (Phone::where('phone_number', $phone_item->number)->exists()) {
          $phone = Phone::where('phone_number', $phone_item->number)->get('id');

          $item_profile->phones()->attach($phone[0]->id);
        } else {
          $phone = new Phone;
          $phone->phone_number = $phone_item->number;
          $phone->phone_type_id = $phone_item->type;
          $phone->save();

          $item_profile->phones()->attach($phone->id);
        }
      }

      return 'Success';
    } catch (\Throwable $th) {
      return 'Error';
      //throw $th;
    }
  }
}

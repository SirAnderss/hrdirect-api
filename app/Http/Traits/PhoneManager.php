<?php

namespace App\Http\Traits;

use App\Phone;

trait PhoneManager
{
  private function insertPhones($phones, $item_profile)
  {
    try {
      for ($i = 0; $i < count($phones); $i++) {

        if (Phone::where('phone_number', $phones[$i]['number'])->exists()) {
          $phone = Phone::where('phone_number', $phones[$i]['number'])->get('id');

          $item_profile->phones()->attach($phone[0]->id);
        } else {
          $phone = new Phone;
          $phone->phone_number = $phones[$i]['number'];
          $phone->phone_type_id = $phones[$i]['type'];
          $phone->save();

          $item_profile->phones()->attach($phone->id);
        }
        echo $phones[$i]['number'] . ' - ';
      }

      return 'Success';
    } catch (\Throwable $th) {
      return 'Error';
      //throw $th;
    }
  }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneProfileTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('phone_profile', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('phone_id')->unsigned();
      $table->uuid('profile_id');
      // $table->timestamps();

      //Relationships
      $table->foreign('phone_id')->references('id')->on('phones')->onDelete('cascade')->onUpdate('cascade');
      $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade')->onUpdate('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('phone_profile');
  }
}

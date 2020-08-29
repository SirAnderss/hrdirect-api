<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhonesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('phones', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('phone_number')->unsigned();
      $table->bigInteger('phone_type_id')->unsigned();
      // $table->timestamps();

      //Relationships
      $table->foreign('phone_type_id')->references('id')->on('phone_types')->onDelete('cascade')->onUpdate('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('phones');
  }
}

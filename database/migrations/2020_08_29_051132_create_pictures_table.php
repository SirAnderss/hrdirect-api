<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePicturesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('pictures', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->bigInteger('picture_link');
      $table->bigInteger('picture_type_id')->unsigned();
      $table->uuid('profile_id');
      // $table->timestamps();

      //Relationships
      $table->foreign('picture_type_id')->references('id')->on('picture_types')->onDelete('cascade')->onUpdate('cascade');
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
    Schema::dropIfExists('pictures');
  }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryProfileTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('category_profile', function (Blueprint $table) {
      $table->id();
      $table->uuid('category_id');
      $table->uuid('profile_id');
      // $table->timestamps();

      //Relationships
      $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
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
    Schema::dropIfExists('category_profile');
  }
}

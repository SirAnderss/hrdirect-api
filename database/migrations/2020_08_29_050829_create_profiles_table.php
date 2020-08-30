<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('profiles', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('name', 70);
      $table->string('slug', 70);
      $table->string('address', 100);
      $table->text('description', 500);
      $table->uuid('user_id');
      $table->softDeletes();
      $table->timestamps();

      //Relationships
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('profiles');
  }
}

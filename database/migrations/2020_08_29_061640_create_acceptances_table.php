<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcceptancesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('acceptances', function (Blueprint $table) {
      $table->id();
      $table->tinyInteger('rating')->unsigned();
      $table->double('avg_rating', 5, 2)->unsigned();
      $table->uuid('profile_id');
      $table->uuid('user_id');
      $table->timestamps();

      //Relationships
      $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade')->onUpdate('cascade');
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
    Schema::dropIfExists('acceptances');
  }
}

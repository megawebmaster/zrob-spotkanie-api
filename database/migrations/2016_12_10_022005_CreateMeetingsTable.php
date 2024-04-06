<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
  public function up(): void
  {
    Schema::create('meetings', function(Blueprint $table){
      $table->increments('id');
      $table->string('hash');
      $table->string('name');
      $table->integer('resolution');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::drop('meetings');
  }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeetingAnswersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('meeting_answers', function (Blueprint $table)
    {
      $table->increments('id');
      $table->integer('meeting_day_hour_id')->unsigned();
      $table->foreign('meeting_day_hour_id', 'meeting_answer')->references('id')->on('meeting_day_hours')->onDelete('cascade');
      $table->string('name');
      $table->string('answer');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('meeting_answers');
  }
}

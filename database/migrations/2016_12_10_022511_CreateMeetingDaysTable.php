<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeetingDaysTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('meeting_days', function (Blueprint $table)
    {
      $table->increments('id');
      $table->integer('meeting_id')->unsigned();
      $table->foreign('meeting_id', 'day_of_meeting')->references('id')->on('meetings')->onDelete('cascade');
      $table->date('day');
      $table->string('from');
      $table->string('to');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('meeting_days');
  }
}

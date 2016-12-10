<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeetingDayHoursTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('meeting_day_hours', function (Blueprint $table)
    {
      $table->increments('id');
      $table->integer('meeting_day_id')->unsigned();
      $table->foreign('meeting_day_id', 'hour_of_meeting')->references('id')->on('meeting_days')->onDelete('cascade');
      $table->time('hour');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('meeting_day_hours');
  }
}

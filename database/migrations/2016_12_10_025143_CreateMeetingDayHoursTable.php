<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingDayHoursTable extends Migration
{
  public function up(): void
  {
    Schema::create('meeting_day_hours', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('meeting_day_id')->unsigned();
      $table->foreign('meeting_day_id', 'hour_of_meeting')->references('id')->on('meeting_days')->onDelete('cascade');
      $table->time('hour');
    });
  }

  public function down(): void
  {
    Schema::drop('meeting_day_hours');
  }
}

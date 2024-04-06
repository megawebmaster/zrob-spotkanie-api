<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingDaysTable extends Migration
{
  public function up(): void
  {
    Schema::create('meeting_days', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('meeting_id')->unsigned();
      $table->foreign('meeting_id', 'day_of_meeting')->references('id')->on('meetings')->onDelete('cascade');
      $table->date('day');
      $table->string('from');
      $table->string('to');
    });
  }

  public function down(): void
  {
    Schema::drop('meeting_days');
  }
}

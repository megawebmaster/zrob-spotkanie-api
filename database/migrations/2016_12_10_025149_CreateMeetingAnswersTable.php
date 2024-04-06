<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingAnswersTable extends Migration
{
  public function up(): void
  {
    Schema::create('meeting_answers', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('meeting_day_hour_id')->unsigned();
      $table->foreign('meeting_day_hour_id', 'meeting_answer')->references('id')->on('meeting_day_hours')->onDelete('cascade');
      $table->string('name');
      $table->string('answer');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::drop('meeting_answers');
  }
}

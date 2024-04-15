<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('meeting_day_hours', function (Blueprint $table) {
      $table->string('hour')->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('meeting_day_hours', function (Blueprint $table) {
      $table->time('hour')->change();
    });
  }
};

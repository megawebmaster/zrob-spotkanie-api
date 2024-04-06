<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AllowEmptyFromAndTo extends Migration
{
  public function up(): void
  {
    Schema::table('meeting_days', function (Blueprint $table) {
      $table->string('from')->nullable()->change();
      $table->string('to')->nullable()->change();
    });
  }

  public function down(): void
  {
    Schema::table('meeting_days', function (Blueprint $table) {
      $table->string('from')->nullable(false)->default('0')->change();
      $table->string('to')->nullable(false)->default('23')->change();
    });
  }
}

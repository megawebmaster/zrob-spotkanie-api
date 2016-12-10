<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingAnswer extends Model
{
  protected $fillable = ['meeting_day_hour_id', 'name', 'answer'];
  protected $hidden = ['meeting_day_hour_id', 'id'];
}

<?php
declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MeetingDayHour extends Model
{
  protected $fillable = ['meeting_day_id', 'hour'];
  protected $hidden = ['meeting_day_id', 'id'];
  public $timestamps = false;

  public function answers(): HasMany
  {
    return $this->hasMany(MeetingAnswer::class);
  }
}

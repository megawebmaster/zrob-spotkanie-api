<?php
declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MeetingDay extends Model
{
  protected $fillable = ['meeting_id', 'day', 'from', 'to'];
  protected $hidden = ['meeting_id', 'id'];
  protected $dates = ['day'];
  public $timestamps = false;

  public function hours(): HasMany
  {
    return $this->hasMany(MeetingDayHour::class);
  }
}

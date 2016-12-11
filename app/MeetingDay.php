<?php
declare(strict_types = 1);

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MeetingDay extends Model
{
  protected $fillable = ['meeting_id', 'day', 'from', 'to'];
  protected $hidden = ['meeting_id', 'id'];
  protected $dates = ['day'];
  public $timestamps = false;

  public function setDayAttribute($value)
  {
    $this->attributes['day'] = Carbon::createFromFormat('Y-m-d\\TH:i:s.uZ', $value);
  }

  public function hours(): HasMany
  {
    return $this->hasMany(MeetingDayHour::class);
  }
}

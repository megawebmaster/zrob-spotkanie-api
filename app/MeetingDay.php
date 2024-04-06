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

  public function setDayAttribute($value): void
  {
    $this->attributes['day'] = Carbon::createFromFormat('Y-m-d\\TH:i:s.uZ', $value);
  }

  public function getDay(): Carbon
  {
    return Carbon::createFromFormat('Y-m-d', $this->getAttribute('day'));
  }

  public function hours(): HasMany
  {
    return $this->hasMany(MeetingDayHour::class);
  }

  public function isFullDay(): bool
  {
    return $this->attributes['from'] == null && $this->attributes['to'] == null;
  }

  protected function serializeDate(\DateTimeInterface $date): string
  {
    return \Illuminate\Support\Carbon::instance($date)->format('Y-m-d');
  }
}

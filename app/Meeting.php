<?php
declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
  protected $fillable = ['name', 'resolution'];
  protected $hidden = ['id'];

  protected static function boot()
  {
    parent::boot();

    static::creating(function(Meeting $model){
      $base = $model->getAttribute('name').microtime().'!'.$model->getAttribute('resolution');
      $model->setAttribute('hash', substr(hash('md5', $base), 2, 10));
    });
  }

  public function days(): HasMany
  {
    return $this->hasMany(MeetingDay::class);
  }
}

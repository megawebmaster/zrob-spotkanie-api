<?php
declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Translation\Translator;

class Validator extends \Illuminate\Validation\Validator
{
  public function __construct(Translator $translator, array $data, array $rules, array $messages = [], array $customAttributes = [])
  {
    parent::__construct($translator, $data, $rules, $messages, $customAttributes);
    $this->dependentRules[] = 'AfterAtLeast';
  }

  public function validateAfterAtLeast($attribute, $value, $parameters): bool
  {
    $this->requireParameterCount(2, $parameters, 'after_at_least');

    $afterValue = $this->getValue($parameters[0]);
    $current = $this->createTimeFromValue($value);
    $before = $this->createTimeFromValue($afterValue);
    $difference = $this->getValue($parameters[1]);
    $current->subMinutes($difference);

    return $before->lte($current);
  }

  private function createTimeFromValue($value): Carbon
  {
    if (!str_contains($value, ':')) {
      $value .= ':00';
    }

    return Carbon::createFromFormat('H:i', $value);
  }

  public function validateSimpleHour($attribute, $value): bool
  {
    return
      $value &&
      preg_match('@^\d{1,2}(:\d{2})?$@', $value) === 1 &&
      $this->createTimeFromValue($value)->lte(Carbon::now()->endOfDay());
  }

  public function validateMeetingResponse($attribute, $value, $parameters): bool
  {
    $this->requireParameterCount(1, $parameters, 'meeting_response');

    /** @var Meeting $meeting */
    $meeting = Meeting::query()->with('days')->where('hash', $parameters[0])->first();
    if (!$meeting) {
      return false;
    }

    $missingDays = $meeting->getAttribute('days')->map(function (MeetingDay $item) use ($meeting, $value) {
      $day = $item->getAttribute('day');

      if (!isset($value[$day])) {
        return false;
      }

      if ($item->isFullDay()) {
        return isset($value[$day][$day]);
      }

      $hoursCheck = $this->_getHoursRange($item, $meeting)->map(function ($hour) use ($value, $day) {
        return isset($value[$day][$hour]);
      });

      return !$hoursCheck->contains(false);
    });

    return !$missingDays->contains(false);
  }

  private function _getHoursRange(MeetingDay $item, Meeting $meeting): Collection
  {
    $start = $this->_getMinutes($item->getAttribute('from'));
    $end = $this->_getMinutes($item->getAttribute('to'));
    $resolution = (int)$meeting->getAttribute('resolution');

    return collect(range($start, $end - $resolution, $resolution))
      ->map(fn($value) => $this->_getTime($value));
  }

  // TODO: Extract _getMinutes and _getTime to a service (used in MeetingsController)
  private function _getMinutes(string $time): int
  {
    if(str_contains($time, ':'))
    {
      $time = explode(':', $time);

      return (int)$time[0] * 60 + (int)$time[1];
    }

    return (int)$time * 60;
  }

  private function _getTime(int $hour): string
  {
    $hours = floor($hour / 60);
    $minutes = $hour - $hours * 60;

    return Carbon::createFromTime($hours, $minutes)->format('H:i');
  }
}

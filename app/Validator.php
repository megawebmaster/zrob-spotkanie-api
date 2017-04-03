<?php
declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Symfony\Component\Translation\TranslatorInterface;

class Validator extends \Illuminate\Validation\Validator
{
  public function __construct(TranslatorInterface $translator, array $data, array $rules, array $messages = [], array $customAttributes = [])
  {
    parent::__construct($translator, $data, $rules, $messages, $customAttributes);
    $this->dependentRules[] = 'AfterAtLeast';
  }

  public function validateAfterAtLeast($attribute, $value, $parameters)
  {
    $this->requireParameterCount(2, $parameters, 'after_at_least');

    $afterValue = $this->getValue($parameters[0]);
    $current = $this->createTimeFromValue($value);
    $before = $this->createTimeFromValue($afterValue);
    $difference = $this->getValue($parameters[1]);
    $current->subMinute($difference);

    return $before->lte($current);
  }

  /**
   * @param $value
   * @return Carbon
   */
  private function createTimeFromValue($value)
  {
    if(strpos($value, ':') === false)
    {
      $value .= ':00';
    }

    return Carbon::createFromFormat('H:i', $value);
  }

  public function validateSimpleHour($attribute, $value)
  {
    return preg_match('@^\d{1,2}(:\d{2})?$@', $value) === 1 &&
      $this->createTimeFromValue($value)->lte(Carbon::now()->endOfDay());
  }

  public function validateMeetingResponse($attribute, $value, $parameters)
  {
    $this->requireParameterCount(1, $parameters, 'meeting_response');

    /** @var Meeting $meeting */
    $meeting = Meeting::query()->with('days')->where('hash', $parameters[0])->first();
    if(!$meeting)
    {
      return false;
    }

    $hasAnyMissingDay = $meeting->getAttribute('days')->map(function(MeetingDay $item) use ($meeting, $value){
      $day = $item->getAttribute('day')->format('Y-m-d');

      return isset($value[$day]) && !$this->_getHoursRange($item, $meeting)->map(function($hour) use ($value, $day){
          return isset($value[$day][$this->_getTime($hour)]);
        })->contains(false);
    })->contains(false);

    return !$hasAnyMissingDay;
  }

  private function _getHoursRange(MeetingDay $item, Meeting $meeting)
  {
    $start = $this->_getMinutes($item->getAttribute('from'));
    $end = $this->_getMinutes($item->getAttribute('to'));
    $resolution = (int)$meeting->getAttribute('resolution');

    return collect(range($start, $end - $resolution, $resolution));
  }

  // TODO: Extract _getMinutes and _getTime to a service (used in MeetingsController)
  private function _getMinutes(string $time): int
  {
    if(strpos($time, ':') !== false)
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

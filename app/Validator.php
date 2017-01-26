<?php
declare(strict_types = 1);

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

    $enhanceValue = function ($value)
    {
      if(strpos($value, ':') === false)
      {
        return $value.':00';
      }

      return $value;
    };

    $afterValue = $this->getValue($parameters[0]);
    $current = Carbon::createFromFormat('H:i', $enhanceValue($value));
    $before = Carbon::createFromFormat('H:i', $enhanceValue($afterValue));
    $difference = $this->getValue($parameters[1]);
    $current->subMinute($difference);

    return $before->lte($current);
  }

  public function validateSimpleHour($attribute, $value)
  {
    return preg_match('@^\d{1,2}(:\d{2})?$@', $value) === 1;
  }
}

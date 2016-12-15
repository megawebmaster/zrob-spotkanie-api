<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Meeting;
use App\MeetingDay;
use App\MeetingDayHour;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MeetingAnswersController extends Controller
{
  public function create(Request $request, string $hash)
  {
    $result = \DB::transaction(function () use ($request, $hash)
    {
      /** @var Meeting $meeting */
      $meeting = Meeting::query()->with('days.hours')->where('hash', $hash)->first();
      if (!$meeting) {
        return false;
      }

      foreach ($request->input('response') as $day => $hours) {
        $dayDate = Carbon::createFromFormat('Y.m.d', $day);
        /** @var MeetingDay $d */
        $d = $meeting->getAttribute('days')->first(function($value) use ($dayDate){
          return $value->day->isSameDay($dayDate);
        });
        if (!$d) {
          return false;
        }

        foreach ($hours as $hour => $answer) {
          /** @var MeetingDayHour $h */
          $h = $d->getAttribute('hours')->first(function($value) use ($hour){
            return $value->hour == $hour;
          });
          if (!$h) {
            return false;
          }

          $result = $h->answers()->create([
            'name' => $request->input('name'),
            'answer' => $answer,
          ]);

          if (!$result) {
            return false;
          }
        }
      }

      return true;
    });

    if($result)
    {
      return response()->json('', 201);
    }

    return response('', 400);
  }
}

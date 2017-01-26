<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Meeting;
use App\MeetingDay;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MeetingsController extends Controller
{
  public function get(string $hash)
  {
    $meeting = Meeting::query()->with('days.hours.answers')->where('hash', $hash)->first();

    if($meeting === null)
    {
      return response('', 404);
    }

    return response()->json($meeting);
  }

  public function create(Request $request)
  {
    $this->validate($request, [
      'name' => 'required|max:255',
      'resolution' => 'required|numeric',
      'schedule' => 'required',
      'schedule.*.day' => 'required|date',
      'schedule.*.from' => 'required|simple_hour',
      'schedule.*.to' => 'bail|required|simple_hour|after_at_least:schedule.*.from,resolution',
    ], [
      'name.required' => 'Nazwa spotkania jest wymagana',
      'name.max' => 'Nazwa spotkania nie może być dłuższa niż 255 znaków',
      'resolution.numeric' => 'Niepoprawny czas spotkania',
      'schedule.required' => 'Musisz wybrać dni spotkania',
      'schedule.*.day' => 'Niepoprawny dzień',
      'schedule.*.from.required' => 'Godzina początkowa jest wymagana',
      'schedule.*.from.simple_hour' => 'Niepoprawna godzina początkowa',
      'schedule.*.to.required' => 'Godzina końcowa jest wymagana',
      'schedule.*.to.simple_hour' => 'Niepoprawna godzina końcowa',
      'schedule.*.to.after_at_least' => 'Godzina końcowa musi uwzględniać czas trwania spotkania',
    ]);

    $result = \DB::transaction(function () use ($request)
    {
      $meeting = Meeting::create($request->all());

      foreach($request->input('schedule') as $day)
      {
        /** @var MeetingDay $meetingDay */
        $meetingDay = $meeting->days()->create($day);
        $start = $this->_getMinutes($meetingDay->getAttribute('from'));
        $end = $this->_getMinutes($meetingDay->getAttribute('to'));
        $resolution = (int)$meeting->getAttribute('resolution');
        $hours = range($start, $end - $resolution, $resolution);

        foreach($hours as $hour)
        {
          $meetingDay->hours()->create(['hour' => $this->_getTime($hour)]);
        }
      }

      return $meeting;
    });

    if($result !== null)
    {
      return response()->json($result, 201);
    }

    return response('', 400);
  }

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

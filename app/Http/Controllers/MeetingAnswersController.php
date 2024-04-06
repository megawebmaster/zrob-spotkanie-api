<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Meeting;
use App\MeetingDay;
use App\MeetingDayHour;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MeetingAnswersController extends Controller
{
  public function create(Request $request, string $hash): Response|JsonResponse
  {
    try {
      $this->validate($request, [
        'name' => 'required|max:255',
        'response' => 'required|meeting_response:' . $hash,
      ], [
        'name.required' => 'Twoje imię jest wymagane',
        'name.max' => 'Imię nie może być dłuższe niż 255 znaków',
        'response.required' => 'Twoje odpowiedzi są konieczne!',
        'response.meeting_response' => 'Nieprawidłowa odpowiedź',
      ]);

      $result = DB::transaction(function () use ($request, $hash) {
        /** @var Meeting $meeting */
        $meeting = Meeting::query()->with('days.hours')->where('hash', $hash)->first();
        if (!$meeting) {
          return false;
        }

        foreach ($request->input('response') as $day => $hours) {
          $dayDate = Carbon::createFromFormat('Y-m-d', $day);
          /** @var MeetingDay $d */
          $d = $meeting->getAttribute('days')->first(function ($value) use ($dayDate) {
            return $value->getDay()->isSameDay($dayDate);
          });
          if (!$d) {
            return false;
          }

          foreach ($hours as $hour => $answer) {
            /** @var MeetingDayHour $h */
            $h = $d->getAttribute('hours')->first(function ($value) use ($hour) {
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

      if ($result) {
        return response()->json('', 201);
      }

      return response('', 400);
    } catch (ValidationException $e) {
      return response()->json($e->errors(), 400);
    }
  }
}

<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
  /**
   * Display calendar.
   */
  public function getCalendar(Request $request): View
  {
    $calendarEvent = CalendarEvent::all();
    $date = empty($date) ? Carbon::now() : Carbon::createFromDate($date);
    $startOfCalendar = $date
      ->copy()
      ->firstOfMonth()
      ->startOfWeek(Carbon::SUNDAY);
    $endOfCalendar = $date
      ->copy()
      ->lastOfMonth()
      ->endOfWeek(Carbon::SATURDAY);

    return view('calendar', [
      'user' => $request->user(),
      'date' => $date,
      'startOfCalendar' => $startOfCalendar,
      'endOfCalendar' => $endOfCalendar,
      'calendarEvent' => $calendarEvent
    ]);
  }

  public function postCalendarEvent(Request $request)
  {
    $calendarEvent = new CalendarEvent();

    $this->validate($request, [
      'date' => 'required|date',
      'event' => 'required|string|max:255',
    ]);

    $calendarEvent->date = $request['date'];
    $calendarEvent->event = $request['event'];
    $calendarEvent->save();

    return redirect()->route('calendar.get-calendar');
  }
}

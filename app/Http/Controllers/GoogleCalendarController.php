<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoogleCalendarController extends Controller
{
    public function insert() {
        $calendar = new Google_Service_Calendar_Calendar();
        $calendar->setSummary('calendarSummary');
        $calendar->setTimeZone('America/Los_Angeles');
        
        $createdCalendar = $service->calendars->insert($calendar);
        
        echo $createdCalendar->getId();        
    }
}

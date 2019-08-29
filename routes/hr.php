<?php

Route::middleware('can:isHR')->group(function() {

    Route::view('/', 'hrmenu');

    Route::get('calendars/{year?}', 'CalendarController@index');
    Route::get('calendar/addholiday/{yr}/{calid?}', function($yr, $calid=0) {
        return view('calendar.addholiday', compact('yr','calid'));
    });
    Route::view('cal/pm', 'calendar.googlepm');    
});

Route::middleware('can:isHRTM')->group(function() {

    Route::post('calendar/addholiday/{year}', 'CalendarController@update');

});
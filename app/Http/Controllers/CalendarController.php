<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
use Auth;

class CalendarController extends Controller
{
    public function index($year=0) {
        if ($year == 0) $year = date('Y');

        // prepare calendar for next year
        $exist = Calendar::year($year+1)->count();
        $month = date('m');
        if ($month > 9 && ! $exist) {
            $this->addcalendar();
        }

        $years = Calendar::year($year)->orderBy('cldate')->get();
        return view('calendar.year',compact('year','years'));
    }

    public function addcalendar() {
        $user = auth()->user();
        $year = date('Y') + 1;
        $month = date('m');
        $exist = Calendar::year($year)->count();
        if ($month > 9 && ! $exist) {
            $firstdate = strtotime($year . '-01-01 00:00:00');
            $lastdate = strtotime($year . '-12-31 00:00:00');
            $day = $firstdate;
            while( date('Y', $day) == $year) {
                $sun = date('w', $day) == 0;
                $sat = date('w', $day) == 6;
                $full = date('Ym', $day);
                $halfof = (date('d', $day) > 15 ? date('Ym', strtotime('+16 day', $day) ) : $full);
                $halfwf = (date('d', $day) > 23 ? date('Ym', strtotime('+8 day', $day) ) : $full);

                $cal = new Calendar;
                $cal->cldate = date('Y-m-d', $day);
                $cal->holiday = ($sun ? 1 : 0);
                
                $cal->of = ($sun || $sat ? 3683 : 3678);
                $cal->ofm = $full;
                $cal->ofo = $halfof;

                $cal->wf = ($sun ? 3683 : 3679);
                $cal->wfm = $halfwf;
                $cal->wfo = $halfwf;

                $cal->created_by = $user->id;
                $cal->updated_by = $user->id;

                $cal->save();

                $day = strtotime('+ 1 day', $day);
            }
        }
    }

    public function edit($yr) {
        $year = date('Y');
        if ( in_array($yr, array($year, $year+1) ) ) {
            $defaultdate = ($yr==$year ? date('Y-m-d', strtotime('-7 day')) : date('Y-m-d', strtotime('today')) );
            return view('calendar.addholiday', compact(['yr','defaultdate']));
        }
    }

    public function update(Request $req) {
        $holidate = $req->input('caldate');
        $mode = $req->input('mode');
        // $calid = $req->input('calid'); is also available
        if ($holidate < date('Y-m-d', strtotime('-450 day'))) {
            return 'Date Passed cannot be edited';
        }
        $holidays = Calendar::where('cldate', $holidate);
        if (! empty($holidays) ) {
            $holiday = $holidays->first();
            $sun = $holiday->daynum == 0;
            if ($mode == 1) {
                $holiday->holiday = 2;
                $holiday->rem = $req->input('calmemo');
            }
            if ($mode == 9) {
                $holiday->holiday = ($sun ? 1 : 0);
                $holiday->rem = null;
            }
            $holiday->save();
        }
        return back();
        // return back()->refresh();
        // return \App::make('redirect')->back()->refresh();
    }
}

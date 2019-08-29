<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('hr', function () {
    return view('hrmenu');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home'); 

Route::group(array('before' => 'auth'), function() {

    Route::view('hr', 'hrmenu')->middleware('can:isHR');
    // Calender
    Route::get('calendars/{year?}', 'CalendarController@index');
    Route::get('calendar/addholiday/{yr}/{calid?}', function($yr, $calid=0) {
        return view('calendar.addholiday', compact('yr','calid'));
    })->middleware('can:isHRTM');
    Route::view('cal/pm', 'calendar.googlepm');    
    // Calender //

    // Organization
    Route::get('orgs/{orgid?}','OrgController@index')->middleware('can:isHR');
    
    Route::get('org/remove/{orgid}','OrgController@remove')->middleware('can:isHRTM');
    Route::get('org/addunder/{orgid}','OrgController@addunder')->middleware('can:isHRTM');
    Route::get('org/move/{underid}/{orgid}','OrgController@moveunder')->middleware('can:isHRTM');
    Route::get('org/rename/{orgid}','OrgController@rename')->middleware('can:isHRTM');
    Route::get('org/add/{parid}', function($parid) {
        return view('org.addorg', compact('parid') )->render();
    })->middleware('can:isHRTM');
    Route::post('org/addorg', 'OrgController@addunder')->middleware('can:isHRTM');
    Route::post('org/relocate', 'OrgController@relocate')->middleware('can:isHRTM');
    Route::post('org/remove', 'OrgController@relocate')->middleware('can:isHRTM');
    Route::get('org/rename/{orgid}', function($orgid) {
        $org = \App\Models\EmpOrganization::find($orgid);
        return view('org.rename', compact('org') )->render();
    })->middleware('can:isHRTM');
    Route::post('org/rename', 'OrgController@rename')->middleware('can:isHRTM');
    Route::get('org/profile/{orgid}', function($orgid) {
        $org = \App\Models\EmpOrganization::find($orgid);
        $parname = $org->parent->fullname;
        return [$parname, view('org.profile', compact('parname','org') )->render()];
    })->middleware('can:isHRTM');
    // Organization //

    //  Job Title
    Route::view('jobs', 'jobs.jobtitle');
    Route::get('jobs/view/{jobid?}', function($jobid) {
        return view('jobs.jobprofile',compact('jobid'));
    });
    Route::get('jobs/{jobid?}','JobController@index');
    Route::get('jobs/emp/{empid}','JobController@emplist');
    Route::get('job/profile/{jobid}', function($jobid) {
        $job = \App\Models\jobtitle::find($jobid);
        $orgname = \App\Models\EmpOrganization::find($job->Org->id)->fullname;
        $parname = $job->parent->name;
        return [$parname, $orgname, view('jobs.profile', compact('job') )->render()];
    });
    Route::post('job/relocate', 'JobController@relocate')->middleware('can:isHRTM');
    Route::get('job/add/{parid}', function($parid) {
        return view('jobs.addjob', compact('parid') )->render();
    })->middleware('can:isHRTM');
    Route::post('job/add', 'JobController@addunder')->middleware('can:isHRTM');
    Route::get('job/rename/{jobid}', function($jobid) {
        return view('jobs.rename', compact('jobid') )->render();
    })->middleware('can:isHRTM');
    Route::post('job/rename', 'JobController@rename')->middleware('can:isHRTM');
    //  Job Title   //



    Route::resource('/{par}/commons', 'CommonsController')->middleware('can:isMaster');
        Route::get('/commons/{id}', 'CommonsController@readindex')->middleware('can:isMaster');
        Route::resource('commons', 'CommonsController')->middleware('can:isMaster');

        
        Route::post('/hrrq/add','hrRequestController@addrq')->middleware('can:isHR');
    Route::get('/hrrq/create','hrRequestController@create')->middleware('can:isHR');
    // Route::get('/hrrq/{yr?}/{open?}','hrRequestController@index');
    Route::get('/hrrq/{yr?}/{open?}','hrRequestController@index')->middleware('can:isHR');
    Route::get('/hrapps/{rqid}','hrAppsController@index')->middleware('can:isHR');
    Route::get('/appdir/{rqid}/{appid}','DirsController@getdir')->middleware('can:isHR');
    Route::post('/appdir/save','DirsController@savedir')->middleware('can:isHR');
    Route::post('/app/afterinterview','hrAppController@saveinterview');
    Route::get('/app/select','hrAppsController@selection')->middleware('can:isHRTM');
    Route::post('/app/selected','hrAppsController@saveselection')->middleware('can:isHR');
    Route::get('/interview/{dirid}','hrAppController@index')->middleware('can:isHR');

    Route::get('/hrcons','hrContractController@index')->middleware('can:isHR');
    Route::post('/hrcons/{yr?}/{type?}','hrContractController@index')->middleware('can:isHR');
    Route::get('/hrcon/{conid}','hrContractController@show')->middleware('can:isHR');
    Route::get('/hrcon/delete','hrContractController@show')->middleware('can:isHR');
    //// ***** /////
    Route::get('job/{jobid}','hrRequestController@getjob')->middleware('can:isHR');

    Route::get('dir','DirsController@findtax')->middleware('can:isMaster');

    Route::resource('dirs', 'DirsController')->middleware('can:isMaster');

    Route::resource('emps', 'EmpsController')->middleware('can:isHR');
    Route::get('emps/{quit}/{cm}/{searchtxt?}', 'EmpsController@index')->middleware('can:isHR');
    Route::get('/emps/{id}', 'EmpsController@show')->middleware('can:isHR');

    Route::resource('users', 'UsersController')->middleware('can:isMaster');
    Route::get('/users/{id}', 'UsersController@readindex')->middleware('can:isMaster');
    Route::get('/dirs/{id}', 'DirsController@show')->middleware('can:isMaster');
    Route::get('/rel/{emp}','RelativesController@index')->middleware('can:isHR');


    Route::get('/interviews/list/{jobid}', 'hrInterviewerController@index')->middleware('can:isHR');
    Route::get('/interviews/create', 'hrInterviewerController@create')->middleware('can:isHR');
    Route::post('/evdecide', 'hrInterviewerController@update')->middleware('can:isHR');
    Route::get('/interviews/score/{interviewid}', function($interviewid) {
        if ($app = \App\Models\hrInterviewer::find($interviewid)) {
            return view('interviewers.putscore',compact('app'));
        }
    })->middleware('auth');

    Route::get('daily/{rqdate?}/{rqgrp?}/{rqchk?}','AttendanceController@index')->middleware('can:isHR');
    Route::get('monthly/{rqmth?}/{rqemp?}','AttendanceController@empindex')->middleware('can:isHR');

    Route::get('/uploadfile/{uid?}','UploadFileController@index');
    Route::post('/uploadfile','UploadFileController@showUploadFile');    

    // Route::get('training/{yr?}/{type?}','TrainingController@index');
    Route::get('training/{yr?}/{type?}',function($year=0, $type=0) {
        return view('training.index', compact('year', 'type'));
    });
    // Route::get('train/{train_id}','TrainingController@show');
    Route::get('train/{train_id}',function($train_id) {
        $train = \App\Models\Training::find($train_id);
        return view('training.show', compact('train'));
    });
    Route::get('train/attendee/add/{trainid}/{attnid?}', function($trainid, $attnid=0) {
        return view('training.attnprofile', compact('trainid', 'attnid'));
    });
    Route::post('train/attn/add','TrainingController@addlist');

    Route::get('empattendance/{empid}/{mth}','AttendanceController@MonthSummary')->middleware('can:isHR');
    Route::get('emppays/{empid}/{yr?}','PayrollController@MonthSummary')->middleware('can:isMaster');

    Route::view('payitems','payrollitem.main');
    Route::get('payitem/card/{itemid}', function($itemid=0) {
        return view('payrollitem.form', compact(['itemid']));
    });

    Route::get('payroll/add', 'PayrollController@add')->middleware('can:isHRTM')->name('payrolls');
    Route::post('payroll/create', 'PayrollController@create')->middleware('can:isHRTM');
    Route::get('payrolls/{yr?}','PayrollsController@index')->middleware('can:isHRTM');
    Route::get('payroll/{payrollid}','PayrollController@index')->middleware('can:isHRTM');
    Route::get('payemp/{payamtid}','PayrollController@payemp')->middleware('can:isHRTM');
    
    Route::view('doctypes','docs.types');
    Route::get('doctype/edit/{typeid}', function($typeid) {
        return view('docs.typeform', compact('typeid') );
    });
    Route::post('doctype/edit', 'DocsController@TypeEdit');

    Route::get('doc/list/{yr?}/{orgid?}/{typeid?}/{ppid?}','DocsController@index');
    Route::get('doc/card/{docid?}','DocsController@show');
//    Route::get('doc/upload/{docid?}','DocsController@upload');
    Route::post('doc/upload','DocsController@upload');
    Route::get( 'docs/{mth?}/{orgid?}/{typeid?}' , 'DocsController@maindoc');

    Route::view('leaveitem','leavemain.main')->middleware('can:isHRTM');
    Route::get('leaveitem/edit/{lvid}', function($lvid) {
        return view('leavemain.edit', ['lvid'=>$lvid]);
    })->middleware('can:isHRTM');
    Route::post('leaveitem/update','LeaveItemController@update');
    
    Route::get('myleave/{yr?}/{empid?}/{type?}','myLeaveController@index');
    Route::get('myleave/list/{yr?}/{empid?}/{type?}',function($yr,$empid,$type) {
        return view('myLeave.myleaves', compact('yr','empid','type') );
    });
    Route::post('myleave','myLeaveController@store');
    
    Route::get('leave/{yr?}', function($yr=0) {
        if (! $yr) $yr = date('Y');
        return view('leave.main', compact(['yr']) );
    });
    Route::get('leave/card/{lvid}', function($lvid) {
        return view('leave.card', compact(['lvid']));
    });
    Route::post('leave/approve', 'LeaveController@approve');
//    Route::get('leave/{yr}/{mode}/{empid?}', 'LeaveController@index');
//    Route::get('leave/{yr}/{mode}/{empid?}', function($yr=0,$mode=0,$empid=0) {
        //$emp = ($empid ? \App\Models\Emp::find($empid) : 0);
//        return view('leave.list', compact(['yr','mode','emp']) );
    //});

    Route::get('leaves/{yr}/{empid?}', function($yr=0,$empid=0) {
        $emp = ($empid ? \App\Models\Emp::find($empid) : 0);
        return view('leave.list', compact(['yr','emp']) );
    });
//    Route::get('leaves/{mode}/{empid}', 'LeaveController@index');

    // Route::view('otorder', 'otorder.main');
    Route::get('otorder/{yr?}', function($yr=0) {
        return view('otorder.main', compact('yr') );
    });
    Route::get('otorder/emps/{underemp}/{yr}/{wk}', function($underemp=0,$yr=0,$wk=0) {
        return view('otorder.list', compact('underemp','yr','wk') );
    });
    Route::get('otorder/attn/{attnid}',function($attnid) {
        return view('otorder.show', compact('attnid'));
    });
    Route::get('otcode/{dt}/{sh}/{ot1}/{ot2}/{ot3}', function($dt,$sh,$ot1,$ot2,$ot3) {
        return date('d-M-Y (D)', strtotime($dt)) . ' [' . worktime($dt,$sh,$ot1,$ot2,$ot3) . ']';
    });
    Route::post('otorder/save','OtController@save');
//    Route::get('sales/{domid?}/{year?}/{month?}','ExportController@index')->middleware('can:isSD'); 
    Route::get('sales/{domid?}/{year?}/{month?}/{page?}', function($domid=1, $year=0, $month=0,$page=1) {
        if(! $year) $year = date('Y');
        if(! $month) $month = date('m');
        return view('export.mainlogs', compact('domid','year', 'month','page') );
    })->middleware('can:isSD'); 
    Route::get('sale/{invid}','ExportController@show')->middleware('can:isSD');
    Route::get('shipdoc/{doc}/{invid}','ShipdocController@invoice')->middleware('can:isSD');
    Route::get('export/item/{invid}/{packid}', function($invid, $packid) {
        return view('export.pages.item', compact('invid', 'packid') );
    });
    Route::get('export/itemsave', 'PackItemController@save');
    Route::get('export/point/{invid}', function($invid) {
        return view('export.elements.pointselect', compact('invid'));
    });
    
    Route::get('points/{customerid?}/{countryid?}/{priceid?}/{payid?}', function($customerid=0,$countryid=0,$priceid=0,$payid=0) {
        return view('export.points.points', compact('customerid','countryid','priceid','payid'));
    });

    Route::get('pointlist/{customerid}/{countryid}/{priceid}/{payid}', function($customerid,$countryid,$priceid,$payid) {
        return view('export.points.pointlist', compact('customerid','countryid','priceid','payid'));
    });
    Route::get('point/create/{customerid}/{countryid}', function($customerid,$countryid) {
        return view('export.points.create', compact('customerid','countryid'));
    });
    Route::post('point/save','PointIdController@save');

    Route::view('export/new','export.new');
    Route::get('export/pointfor/{customerid}', function($customerid) {
        return view('export.elements.pointoption', compact('customerid'));
    });
    Route::post('export/save','ExportController@save');
    Route::get('update/inv/pointid/{recid}/{data}', 'ExportController@updatepoint');

    Route::get('credit/create/{crid}/{arid}', function($crid, $arid) {
        return view('export.credit.newcredit', compact('crid','arid'));
    });

    Route::get('booking/{yymm?}', function($yymm=0) {
        $yymm = (strlen($yymm) == 6 ? $yymm : date('Ym'));
        return view('export.booking.main', compact('yymm') );
    });
    Route::get('booking/view/{bookid}', function($bookid) {
        return view('export.booking.view', compact('bookid') );
    });
    Route::post('booking/add', 'BookingController@addfrominv');

    Route::get('assets/{item?}/{team?}/{pic?}/{state?}','AssetController@index');
    Route::get('asset/{assetid?}','AssetController@show');


    Route::view('products','export.products.main');
    Route::get('products/{igroup?}/{itype?}', function($igroup=3151,$itype=99) {
        if (in_array($igroup, array('2','24','336'))) $itype = 99;
        return view('export.products.list',compact(['igroup', 'itype']));
    });
    Route::get('product/{itemid}', function($itemid) {
        return view('export.products.form', compact('itemid'));
    });
    
    
    // Route::post('doctype/edit', 'DocsController@TypeEdit');
    
    Route::get('countries/{zoneid?}/{countryid?}',function($zoneid=0, $countryid=0) {
        return view('country.main', compact('zoneid','countryid'));
    });
    Route::get('countryof/{levelid}/{parid}/{itemid?}',function($levelid=0, $parid=0, $itemid=0) {
        return view('country.countries', compact('levelid','parid','itemid'));
    });
    Route::get('country/{itemid?}/{grp?}/{par?}', function($itemid=0, $grp=0, $par=0) {
        return view('country.form', compact('itemid','grp','par'));
    });
    Route::post('country/save', 'CountriesController@save');

    Route::middleware('can:isMaster')->group(function() {    
        Route::get('file/common', 'files\CommonFile@xcommons');
        Route::get('file/cal', 'files\CommonFile@xhctcal');
        Route::get('file/country', 'files\CommonFile@xcountry');

        Route::get('file/doc', 'files\CommonFile@xdoc');

        Route::get('file/asset', 'files\CommonFile@xasset');
        Route::get('file/assets', 'files\CommonFile@xassets');


        Route::get('file/emp', 'files\CommonFile@xemps');
        Route::get('file/emps', 'files\CommonFile@xempdat');
        Route::get('file/emppromote', 'files\CommonFile@xemppromote');
        Route::get('file/empbenefit', 'files\CommonFile@xempbenefit');

        Route::get('file/hrrequest', 'files\CommonFile@xhrrequest');
        Route::get('file/hrapplicant', 'files\CommonFile@xhrapplicants');
        Route::get('file/hrcontract', 'files\CommonFile@xhrcontract');

        Route::get('file/attn', 'files\CommonFile@xattn');
        Route::get('file/leave', 'files\CommonFile@xleaverequest');
        Route::get('file/attns', 'files\CommonFile@xattns');
        Route::get('file/train', 'files\CommonFile@xtrain');
        Route::get('file/trains', 'files\CommonFile@xtrains');

        Route::get('file/payroll', 'files\CommonFile@xpayroll');
        Route::get('file/payrollemp', 'files\CommonFile@xpayrollemp');
        Route::get('file/payrollamt', 'files\CommonFile@xpayrollamt');


        Route::get('file/sapitem', 'files\CommonFile@xsapitem');
        Route::get('file/pointid', 'files\CommonFile@xmkpoint');
        Route::get('file/credit', 'files\CommonFile@xmkcredit');
        Route::get('file/booking', 'files\CommonFile@xmkbook');
        Route::get('file/vessel', 'files\CommonFile@xmkvessel');
        Route::get('file/invoice', 'files\CommonFile@xmkinvoice');
        Route::get('file/customs', 'files\CommonFile@xmkcustoms');
        Route::get('file/vat', 'files\CommonFile@xmkvat');
        Route::get('file/packinglist', 'files\CommonFile@xmkpackinglist');
        Route::get('file/load', 'files\CommonFile@xmkload');
        Route::get('file/loads', 'files\CommonFile@xmkloaditem');

    });
});


@php($days = \App\Models\Calendar::whereYear('cldate', $yr)->whereRaw("week(cldate,1)=".$wk)->orderBy('cldate')->groupBy('cldate')->get() )
@php($under = \App\Models\Emp::find($underemp) ?? 0 )
@php($emps = ($under ? $under->LowerEmps->WorkDuring($days->min('cldate'), $days->max('cldate'))->orderBy('jobid')->get() : 0) )
<div class="tbl-header">
  <table class="table table-sm">
    <thead>
      <tr class="header text-center">
        <th width="7%">ID</th>                
        <th>Employee</th>                
        <th>Job Title</th>
        @foreach($days as $day)
        <th width="7%">{!! date('d/m (D)', strtotime($day->cldate)) !!}</th>
        @endforeach
      </tr>
    </thead>
  </table>
</div>

<div class="tbl-content">
  <table class="table table-sm">
    <tbody>
    @if($emps)
    @php($ln=1)
    @foreach($emps as $emp)
      <tr>
        <td class="text-right" width="7%">#{{ $ln++ }})</td>
        <td><a emp-id="{{ $emp->id }}">{{ $emp->fullname }}</a></td>
        <td><a job-id="{{ $emp->jobid }}">{{ $emp->curjob->name }}</a></td>
        @foreach($days as $day)
          @php($atn = \App\Models\AtWork::EmpDate($emp->id, $day->cldate)->first() )
          @php($class ='')
          @php($class .= $day->holiday ? " bg-warning" : '' )
          @php($class .= $day->holiday==2 ? " font-weight-bold" : '' )
          @php($class .= date('Ymd', strtotime($day->cldate)) == date('Ymd', strtotime($emp->indate)) ? " bg-success" : '')
          @php($class .= date('Ymd', strtotime($day->cldate)) == date('Ymd', strtotime($emp->qdate)) ? " bg-danger" : '')
          <td align="center" width="7%"{!! $class > "" ? ' class="' . $class . '"' : '' !!}>
            @if(! empty($atn) )
            <a class="attn" data-id="{{ $atn->w_id ?? 0 }}" data-toggle="modal" data-target="#ModalLong">{{ $atn->workorder ?? '-' }}</a>
            @endif
          </td>
        @endforeach
      </tr>
    @endforeach
    @else
      <tr><td class="h3 text-center">No Record</td></tr>
    @endif
    </thead>
  </tbody>
</div>


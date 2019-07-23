@if(empty($job))
  @php( $job = \App\Models\jobtitle::find($jobid) )
  @php( $emps = ($jobid ? \App\Models\Emp::Ofjob($jobid)->isActive()
          ->with(['promotion','pos'=>function($q) {
            $q->selectRaw('*, num as positionnum') 
              ->orderBy('positionnum');
            }])->get()->sortBy('positionnum') : 0)
)
@endif

<div class="h3"><b><a id="profile" data-id="{{ $job->id }}" data-toggle="modal" data-target="#ModalLong">Job Title : {{ $job->name  }}</a></b></div>
<table class="table table-striped table-hover table-sm">
<thead>
  <tr class="bg-primary">
    <th>No.</th>
    <th>Position</th>
    <th>Employee</th>
    <th>Employed</th>
    <th>Duration</th>
    <th>Promoted</th>
    <th>Active</th>
  </tr>
</thead>
@if( $emps->count() )
  @php($ln=1)
  @foreach( $emps as $emp )
    @php($promotion = $emp->promotion->first->toJson() )
  <tr>
    <td class="text-right">{!! $ln++ !!}. </td>
    <td>{{ $emp->pos->code }}</td>
    <td><a onclick="viewempjob({{ $emp->id }});" data-toggle="modal" data-target="#ModalLong">{{ $emp->name }}<a></td>
    <td>{{ edate($emp->indate) }}</td>
    <td>{{ age($emp->indate)  }}</td>
    <td>{{ edate($promotion->indate) }}</td>
    <td>{{ age($promotion->indate)  }}</td>
  </tr>
  @endforeach
@else
  <tr><td colspan=5 class="text-center"><i>-Vacant-</i></td></tr>
@endif
</table>
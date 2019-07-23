<div class="h3"><b>{!! $emp->fullname . ' ['.$emp->nm . ']' !!}</b></div>
<div class="h4">Employed on {!! edate($emp->indate) . ' ('. age($emp->indate) . ')' !!}</div>
<table class="table table-striped table-hover table-sm">
<thead>
  <tr class="bg-primary">
    <th>No.</th>
    <th>Date</th>
    <th>Until</th>
    <th>Class</th>
    <th>Org</th>
    <th>Post</th>
    <th>Job Title</th>
    <th>Status</th>
  </tr>
</thead>
  @php($ln=1)
  @foreach( $emp->promotion as $promote )
  <tr>
    <td class="text-right">{!! $ln++ !!}. </td>
    <td>{{ edate($promote->indate) }}</td>
    <td>{!! ! empty($promote->xdate) ? edate($promote->xdate) : "<i>-Present-</i>" !!}</td>
    <td>{{ $promote->cls ?? ''}}</td>
    <td title="{!! $promote->EmpOrg->name . ' ' . $promote->EmpOrg->des !!}">
      {!! $promote->EmpOrg->code !!}</td>
    <td title="{!! $promote->EmpPost->name !!}">
      {!! $promote->EmpPost->code !!}</td>
    <td>{!! $promote->EmpJob->name ?? '' !!}</td>
    <td>{!! $promote->on ? 'Active' : 'Inactive' !!}</td>
  </tr>
  @endforeach
</table>
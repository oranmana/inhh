@php($trainings = $emp->trainings)
<table class="table table-sm table-bordered ">
  <thead>
    <tr class="table-info" align="center">
      <th>Date</th>
      <th>Code</th>
      <th>Type</th>
      <th>Course</th>
      <th>Hours</th>
      <th>Cost</th>
    </tr>
  <thead>
  @if($trainings)
  <tbody>
  @foreach ($trainings as $trs)
  @php($tr = $trs->train)
  <tr id="{!! $trs->did !!}">
    <td nowrap align="center">{{ edate($tr->ondate) }}</td>
    <td nowrap><a href="{{ url('train').'/'.$tr->id }}">{!! $tr->code !!}</a></td>
    <td nowrap class="small">{!! ($tr->category ? $tr->category->name : '') !!}</td>
    <td>{{ $tr->coursename }}</td>
    <td align="right">{{ fnum($trs->trainhours,0,0,5) }}</td>
    <td align="right">{{ fnum($trs->Amount,0,0,5) }}</td>
  </tr>
  @endforeach
  </tbody>
  <tfoot>
    <tr class="bg-info">
      <td colspan=4 align="center">TOTAL : {{ $trainings->count() }} Courses</td>
      <td align="right">{{ fnum($trainings->sum('trainhours'),0,0,2) }}</td>
      <td align="right">{{ fnum($trainings->sum('Amount'),0,0,2) }}</td>
    </tr>
  </tfoot>
  @endif

</table>
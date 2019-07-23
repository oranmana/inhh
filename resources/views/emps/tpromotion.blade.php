@php($promotes = $emp->promotion)
@php($icon = array('clear','done','add','add_circle_outline') )
<table class="table table-sm table-bordered ">
  <thead>
    <tr class="table-info" align="center">
      <th>Ref</th>
      <th>From</th>
      <th>To</th>
      <th>Class</th>
      <th>Position</th>
      <th>Org</th>
      <th>Job Title</th>
      <th>Remark</th>
      <th>Status</th>
    </tr>
  <thead>
  @if($promotes)
  <tbody>
  @foreach ($promotes as $pm)
  <tr id="{!! $pm->did !!}" align="center">
    <td align="left">{!! ($pm->doc ? $pm->doc->doccode : '') !!}</td>
    <td>{{ edate($pm->indate) }}</td>
    <td>{!! $pm->xdate ? edate($pm->xdate) : '<i>-Present-</i>' !!}</td>
    <td>{!! ($pm->EmpClass ? $pm->EmpClass->name : '') !!}</td>
    <td>{{ $pm->EmpPost->code }}</td>
    <td>{{ $pm->EmpOrg->code }}</td>
    <td align="left">{{ ($pm->EmpJob ? $pm->EmpJob->name : '') }}</td>
    <td align="left">{{ $pm->rem }}</td>
    <td><i class="Tiny material-icons">{{ $icon[$pm->on] }}</i></td>
  </tr>
  @endforeach 
  </tbody>
  @endif
</table>
@php($pays = $emp->payitems)
<table class="table table-sm table-bordered ">
  <thead>
    <tr class="table-info" align="center">
      <th rowspan=2>Ref</th>
      <th colspan=2>Period</th>
      <th rowspan=2>Wage</th>
      <th colspan=7>Allowance</th>
      <th rowspan=2>Food<br>Allowance</th>
      <th colspan=3>Incentives</th>
      <th rowspan=2>Remark</th>
    </tr>
    <tr class="table-success" align="center">
      <th>From</th>
      <th>To</th>
      <th>Class</th>
      <th>Position</th>
      <th>Job</th>
      <th>Living</th>
      <th>Tuition</th>
      <th>Fuel</th>
      <th>Housing</th>
      <th title="Professionality">Prof.</th>
      <th title="Communication">Comm.</th>
      <th title="Office Relocation">Reloc.</th>
    </tr>
  <thead>
  @if($pays)
  <tbody>
  @foreach ($pays as $py)
  <tr id="{!! $py->did !!}" align="right">
    <td align="left">{!! ($py->Document ? $py->Document->doccode : '') !!}</td>
    <td align="center">{{ edate($py->indate) }}</td>
    <td align="center">{!! ($py->xdate ? edate($py->xdate) : '<i>-Present-</i>') !!}</td>
    <td>{{ fnum($py->wage,0,0,2) }}</td>
    <td>{{ fnum($py->cls,0,0,2) }}</td>
    <td>{{ fnum($py->pos,0,0,2) }}</td>
    <td>{{ fnum($py->job,0,0,2) }}</td>
    <td>{{ fnum($py->live,0,0,2) }}</td>
    <td>{{ fnum($py->edu,0,0,2) }}</td>
    <td>{{ fnum($py->trans,0,0,2) }}</td>
    <td>{{ fnum($py->house,0,0,2) }}</td>
    <td>{{ fnum($py->food,0,0,2) }}</td>
    <td>{{ fnum($py->prof,0,0,2) }}</td>
    <td>{{ fnum($py->comm,0,0,2) }}</td>
    <td>{{ fnum($py->omove,0,0,2) }}</td>
    <td align="left">{{ $py->rem }}</td>
    <td><i class="Tiny material-icons">{{ $py->on ? 'done' : 'clear' }}</i></td>
  </tr>
  @endforeach
  </tbody>
  @endif
</table>
@php($attendances = $emp->AttendanceMonths($year))
<table class="table table-sm table-hover ">
  <thead>
    <tr class="table-info" align="center">
      <th>Month</th>
      <th>Work<br><small>Hours</small></th>
      <th>Late</th>
      <th>Early<br>Left</th>
      <th>H.Work</th>
      <th>H.OT</th>
      <th>OT</th>
      <th>Leave<br><small>Day</small></th>
    </tr>
  <thead>
  <tbody>
  @foreach ($attendances as $atm)
  <tr id="{!! $atm->mth !!}" align="right">
    <td align="left">{!! $atm->name !!}</td>
    <td class="bg-info">{{ fnum($atm->w1h+$atm->w2h,0,0,2) }}</td>
    <td>{{ fnum($atm->lh1h,0,0,2) }}</td>
    <td>{{ fnum($atm->lh2h,0,0,2) }}</td>
    <td>{{ fnum($atm->ot10h+$atm->ot20h,0,0,2) }}</td>
    <td>{{ fnum($atm->ot30h,0,0,2) }}</td>
    <td>{{ fnum($atm->ot15h,0,0,2) }}</td>
    <td>{{ fnum($atm->lvday,0,0,2) }}</td>
  </tr>
  @endforeach
  <tr class="bg-info" align="right">
    <td align="center">TOTAL</td>
    <td>{{ fnum($attendances->sum('w1h')+$attendances->sum('w2h'),0,0,2) }}</td>
    <td>{{ fnum($attendances->sum('lh1h'),0,0,2) }}</td>
    <td>{{ fnum($attendances->sum('lh2h'),0,0,2) }}</td>
    <td>{{ fnum($attendances->sum('ot10h')+$attendances->sum('ot20h'),0,0,2) }}</td>
    <td>{{ fnum($attendances->sum('ot30h'),0,0,2) }}</td>
    <td>{{ fnum($attendances->sum('ot15h'),0,0,2) }}</td>
    <td>{{ fnum($attendances->sum('lvday'),0,0,2) }}</td>
  </tr>
  </tbody>
</table>
@php($month = ( isset($year) && ($year > 0 ) ? 1 : 0) )
@if( $month )
  <h2><a class="payyr" onclick='getpay(0);'>{{ $year }} Payroll</a></h2>
@endif
<table class="table table-sm table-hover">
  <thead>
    <tr class="table-info" align="center">
      <th>Year</th>
      <th>TOTAL Income</th>
      <th title='W/H Tax'>Tax</th>
      <th title='Social Security'>SSF</th>
      <th title='Provident Fund'>PVF</th>
      <th>TOTAL Deduction</th>
      <th>Net Received</th>
    </tr>
  <thead>
  <tbody>
  @foreach ($pays as $pay)
  <tr align="right">
    <td {!! ($month ? '' : " class='payyr' onclick='getpay(". $pay->yr . ");'") !!} align="left">{!! $pay->yr !!}</td>
    <td class="bg-info">{!! fnum($pay->income + $pay->deduct,2) !!}</td>
    <td class="small">{!! fnum($pay->tax,2) !!}</td>
    <td class="small">{!! fnum($pay->ssf,2) !!}</td>
    <td class="small">{!! fnum($pay->pvf,2) !!}</td>
    <td class="bg-info">{!! fnum($pay->deduct,2) !!}</td>
    <td>{!! fnum($pay->income,2) !!}</td>
  </tr>
  @endforeach
  </tbody>
  @if( $month )
  <tr align="right" class='bg-info'>
    <td>TOTAL</td>
    <td >{!! fnum($pays->sum('income') + $pays->sum('deduct'),2) !!}</td>
    <td class="small">{!! fnum($pays->sum('tax'),2) !!}</td>
    <td class="small">{!! fnum($pays->sum('ssf'),2) !!}</td>
    <td class="small">{!! fnum($pays->sum('pvf'),2) !!}</td>
    <td class="bg-info">{!! fnum($pays->sum('deduct'),2) !!}</td>
    <td>{!! fnum($pays->sum('income'),2) !!}</td>
  </tr>
  @endif
</table>
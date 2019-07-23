@php ($lvs = \App\Models\AtWork::EmpYear($empid, $yr)->LeaveOfType($type) )
@php( $leaves = $lvs->get() )
@php( $leave = \App\Models\Leave::find($type) )
<div class="h3">{{ $yr }} {{ $leave->name }}</div>
<table class="table table-stripped">
  <thead>
    <tr class="trhead">
      <th>Request ID</th>
      <th>Date</th>
      <th>Hours</th>
      <th>Days</th>
      <th>Remarks</th>
    </tr>
  </thead>
  <tbody>
  @foreach($leaves as $lv) 
    <tr data-id="{{ $lv->w_id }}">
      <td>{{ $lv->w_lvid }}</td>
      <td nowrap>{{ edate($lv->w_date,14) }}</td>
      <td align=right>{{ fnum($lv->attn->lvh) }}</td>
      <td align=right>{{ fnum($lv->attn->lvd,2) }}</td>
      <td>{{ $lv->lvrq->rem ?? '' }}</td>
    </tr>
  @endforeach
  @php($lvh = $lvs->with('attn')->get()->sum('attn.lvh') )
  <tr id="sumlv" class="bg-info">
    <td colspan=2 align=center>TOTAL</td>
    <td align=right>{{ $lvh }}</td>
    <td align=right>{{ $lvs->with('attn')->get()->sum('attn.lvd') }}</td>
    <td>&nbsp;</td>
  </tr>
</tbody>

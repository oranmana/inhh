@extends('layouts.app')

@section('content')
<div class="container">
  <div>
    <h2 class="text-info"><a href="{{ url('/home') }}">Daily Attendance - OT & Leave Verification</a></h2>
    <div class="float-left form-inline">
      <label class="form-control btn-primary" for="rqdate">Date </label>
      <input type="text" id="todate" value="{!! date('d-M-Y (D)', strtotime($rqdate)) !!}"  class="form-control">
      <input type="date" id="rqdate" name="rqdate" value="{{ $rqdate }}"  class="form-control">
      <label  class="form-control btn-primary" for="rqgrp">Group </label>
      <select id="rqgrp" name="rqgrp" class="form-control">
        <option value=0>-All-</option>
        @foreach($empgrps as $grp)
        <option value="{{ $grp->id }}"{!! $rqgrp == $grp->id ? " SELECTED" : "" !!}>{{ '['.$grp->ref .'] ' . $grp->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="float-right">
      <div class="form-inline">
        <a id="btnupload" class="btn btn-sm" data-toggle="modal" data-target="#ModalLong">Upload</a>
        <a id="btnverify" class="btn btn-sm">Verify</a>
        <a id="btnincomplet" class="btn btn-sm">Incomplete</a>
      </div>
    </div>
  </div>
  <table class="table table-sm table-hover">
    <thead>
      <tr align="right">
        <th>No.</th>
        <th style="text-align:left" class="ename">Employee</th>
        <th style="text-align:left" class="tname">ชื่อ</th>
        <th title='Work Order'>Order</th>
        <th>HL</th>
        <th>WkID</th>
        <th title='Total Work House' class="bg-secondary">WHr</th>
        <th title='Week Day OT Work'>OT15</th>
        <th title='Holiday Work'>OT10</th>
        <th title='Holiday Work (Daily)'>OT20</th>
        <th title='Holiday OT'>OT30</th>
        <th>Leave</th>
        <th>Request</th>
        <th>Day</th>
      </tr>
    </thead>
    @php ($ln = 1)
    <tbody>
      @foreach($wks as $wk)
        @php ($emp = $wk->emp)
        @php ($atn = $wk->attn)
        @php ($lvrq = $wk->lvrq)
        @php ($vlvrq = $wk->vflvrq)
        <tr align="right" data-id="{{ $wk->w_id }}" data-oid="{{ $wk->at_id }}" data-emp="{{ $emp->id }}">
          <td>{{ $ln++ . ')'}}</td>
          <td align="left" class=ename>{{ ($emp ? $emp->name : $wk->empid) }}</td>
          <td align="left" class=tname>{{ ($emp ? $emp->thname : $wk->empid) }}</td>
          <td class="small">{!! $wk->workorder . ($lvrq && $wk->w_lvid > 0 
            ? '/<a class="lvrq" data-id="' . $wk->w_lvid . '" title="' .  $lvrq->lv->name . '">'
            .  $lvrq->lv->code . "</a>" 
          : ($wk->w_lvid ? '/<span class="text-danger">'.$wk->w_lvid.'</span>' : '') ) !!}</td>
          <td>{{ fnum($wk->cal->holiday,0) }}</td>
          <td>{{ $wk->wk->code }}</td>
          <td class="bg-secondary">{{ fnum( array_sum($wk->WkHours),0,0,2) }}</td>
          <td>{{ fnum($wk->OtH15,0,0,2) }}</td>
          <td>{{ fnum($wk->OtH10,0,0,2) }}</td>
          <td>{{ fnum($wk->OtH20,0,0,2) }}</td>
          <td>{{ fnum($wk->OtH30,0,0,2) }}</td>
          <td class="bg-secondary"{!! ' title="' . $vlvrq->name . '">' . $vlvrq->code !!}</td>
          <td>{!! ($vlvrq->id > 0 ? '#'.$vlvrq->id : '') !!}</td>
          <td>{{ fnum($vlvrq->day,0,0,2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@include('partials.modal')
@endsection

@section('script')
<script>
  $(document).ready(function() {
    // Header Menu
    $('#rqdate, #rqgrp').on('change', function() {headrelocate();});
    $('#rqdate').hide();
    $('#todate').on('focusin', function() {
      $('#todate, #rqdate').toggle();
      $('#rqdate').focus();
    });
    $('#rqdate').on('focusout', function() {
      $('#todate, #rqdate').toggle();
    });
    // Name language switch
    $('.tname').hide();
    $('th.tname, th.ename').on('click', function() {
      $('.ename, .tname').toggle();
    });
    // Document.Ready()
  });

  function headrelocate() {
    let date = $('#rqdate').val();
    let grp = $('#rqgrp').val();
    let url ="{!! url('daily') !!}" + '/'+date+'/'+grp+'/2';
    window.location = url;
  }
</script>
@endsection

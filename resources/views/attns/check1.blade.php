@extends('layouts.app')

@section('content')
<div class="container">
  <div>
    <h2 class="text-info"><a href="{{ url('/home') }}">Daily Attendance - Time Verification</a></h2>
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
        <th title="Actual Time-In">In</th>
        <th title="Actual Time-Out">Out</th>
        <th title="Actual Session Time-In">In</th>
        <th title="Actual Session Time-Out">Out</th>
        <th>WkID</th>
        <th>HL</th>
        <!-- <th title='Work Hours'>w1</th>
        <th title='Session Work Hours'>w2</th> -->
        <th title='Working Hours'>W1</th>
        <th title='OT Hours'>W2</th>
        <th title='Late Work Time-In' class="bg-secondary">LM11</th>
        <th title='Late Work Time-Out' class="bg-secondary">LM12</th>
        <th title='Late Session Time-In' class="bg-secondary">LM21</th>
        <th title='Late Session Time-Out' class="bg-secondary">LM22</th>
        <th title='Late Standard Time-In (Hr.)'>LH1</th>
        <th title='Early Leave Standard Time-Out (Hr.)'>LH2</th>
        <th title='Late Scores'>Sc</th>
        <th title='OT Early' class="bg-secondary">pOT</th>
        <th title='OT Post Hour' class="bg-secondary">OTp</th>
        <th title='OT Session' class="bg-secondary">sOTs</th>
      </tr>
    </thead>
    @php ($ln = 1)
    <tbody>
      @foreach($wks as $wk)
        @php ($emp = $wk->emp)
        @php ($atn = $wk->attn)
        @php ($lvrq = $wk->lvrq)
        <tr align="right" data-id="{{ $wk->w_id }}" data-oid="{{ $wk->at_id }}" data-emp="{{ $emp->id }}">
          <td>{{ $ln++ . ')'}}</td>
          <td align="left" class=ename>{{ ($emp ? $emp->name : $wk->empid) }}</td>
          <td align="left" class=tname>{{ ($emp ? $emp->thname : $wk->empid) }}</td>
          <td class="small">{!! $wk->workorder . ($lvrq && $wk->w_lvid > 0 
            ? '/<a class="lvrq" data-id="' . $wk->w_lvid . '" title="' .  $lvrq->lv->name . '">'
            .  $lvrq->lv->code . "</a>" 
          : ($wk->w_lvid ? '/<span class="text-danger">'.$wk->w_lvid.'</span>' : '') ) !!}</td>
          <td {!! $wk->timeready ? '' : 'class=bg-danger' !!}>{!! edate($wk->tin,21) !!}</td>
          <td {!! $wk->timeready ? '' : 'class=bg-danger' !!}>{!! edate($wk->tout,21) !!}</td>
          <td {!! $wk->timeready ? '' : 'class=bg-danger' !!}>{!! edate($wk->tin2,21) !!}</td>
          <td {!! $wk->timeready ? '' : 'class=bg-danger' !!}>{!! edate($wk->tout2,21) !!}</td>
          <td>{{ $wk->wk->code }}</td>
          <td>{{ fnum($wk->cal->holiday,0) }}</td>
          <!-- <td class="small">{{ fnum($wk->WkHrs[1],0,0,2) }}</td>
          <td class="small">{{ fnum($wk->WkHrs[2],0,0,2) }}</td> -->
          <td>{{ fnum($wk->WkHours[1],0,0,2) }}</td>
          <td>{{ fnum($wk->WkHours[2],0,0,2) }}</td>
          <td class="bg-secondary">{{ fnum($wk->Lts[1],0,0,2) }}</td>
          <td class="bg-secondary">{{ fnum($wk->Lts[2],0,0,2) }}</td>
          <td class="bg-secondary">{{ fnum($wk->Lts[3],0,0,2) }}</td>
          <td class="bg-secondary">{{ fnum($wk->Lts[4],0,0,2) }}</td>
          <td class="small">{{ fnum($wk->Lates[1],0,0,2) }}</td>
          <td class="small">{{ fnum($wk->Lates[2],0,0,2) }}</td>
          <td>{{ fnum(array_sum($wk->LateScore),0) }}</td>
          <td class="bg-secondary">{{ fnum($wk->OtHrs[1],0,0,2) }}</td>
          <td class="bg-secondary">{{ fnum($wk->OtHrs[2],0,0,2) }}</td>
          <td class="bg-secondary">{{ fnum($wk->OtHrs[4],0,0,2) }}</td>
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
    let url ="{!! url('daily') !!}" + '/'+date+'/'+grp+'/1';
    window.location = url;
  }
</script>
@endsection

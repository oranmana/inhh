@extends('layouts.app')

@section('content')
<div class="container">
  <div>
    <h2 class="text-info"><a href="{{ url('/home') }}">Daily Attendance</a></h2>
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
        <th style="text-align:left" class="jobt">Job Title</th>
        <th title='Work Order'>Order</th>
        <th>In</th>
        <th>Out</th>
        <th title='OT session time-in'>In</th>
        <th title='OT session time-out'>Out</th>
        <th title='Work Hours'>Hrs</th>
        <th title='OT Hours'>OT</th>
        <th title='(Minute)'>Late</th>
        <th title='Verified Leave'>Leave</th>
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
          <td align="left" class="jobt small" job="{{ $emp->jobid }}">{{ ($emp ? $emp->curjob->name : $wk->empid) }}</td>
          <td class="small">{!! $wk->workorder . ($lvrq && $wk->w_lvid > 0 
            ? '/<a class="lvrq" data-id="' . $wk->w_lvid . '" title="' .  $lvrq->lv->name . '">'
            .  $lvrq->lv->code . "</a>" 
          : ($wk->w_lvid ? '/<span class="text-danger">'.$wk->w_lvid.'</span>' : '') ) !!}</td>
          <td {!! $wk->timeready ? '' : 'class=bg-danger' !!}>{!! edate($wk->tin,21) !!}</td>
          <td {!! $wk->timeready ? '' : 'class=bg-danger' !!}>{!! edate($wk->tout,21) !!}</td>
          <td {!! $wk->timeready ? '' : 'class=bg-danger' !!}>{!! edate($wk->tin2,21) !!}</td>
          <td {!! $wk->timeready ? '' : 'class=bg-danger' !!}>{!! edate($wk->tout2,21) !!}</td>
          <td>{!! fnum(($atn ? $atn->WorkHrs : '0'),0,0,2) !!}</td>
          <td>{!! ($atn ? $atn->otcode : 0) !!}</td>
          <td>{!! fnum(($atn ? $atn->latehrs : 0),0,0,2) !!}</td>
          <td>{!! ($atn ? $atn->lvcode : '') !!}</td>
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
    // Job Title filter
    $('th.jobt').attr('flag',0);
    $('td.jobt').on('click', function() {
      if($('th.jobt').attr('flag')==0) {
        let jobt = $(this).attr('job');
        $('td.jobt[job!='+jobt+']').closest('tr').hide();
        $('th.jobt').attr('flag',1);
      } else {
        $('td.jobt').closest('tr').show();
        $('th.jobt').attr('flag',0);
      }
    });
    // View Emp Monthly
    $('.ename, .tname').on('click', function() {
      let mth = "{!! date('Ym',strtotime($rqdate)) !!}";
      let emp = $(this).closest('tr').attr('data-emp');
      let url ="{!! url('monthly') !!}" + '/'+mth+'/'+emp;
      window.location = url;
    });
    // Upload Bottom
    $('#btnupload').on('click', function() {
      let url = "{!! url('/uploadfile/1') !!}";
      $.ajax({
        url : url
      }).done(function(data) {
        $('#ModalLongTitle').text('Daily Attendance Upload');
        $('.modal-footer').hide();
        $('#ModalBody').empty().html(data);
      });
    });
    // Document.Ready()
  });

  function headrelocate() {
    let date = $('#rqdate').val();
    let grp = $('#rqgrp').val();
    let url ="{!! url('daily') !!}" + '/'+date+'/'+grp;
    window.location = url;
  }
</script>
@endsection

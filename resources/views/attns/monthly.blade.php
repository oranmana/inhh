@extends('layouts.app')

@section('content')
<div class="container">
  <div>
    <h2 class="text-info"><a href="{{ url('/home') }}">Daily Attendance</a></h2>
    <div class="float-left form-inline">
      <label class="form-control btn-primary" for="rqdate">Employee </label>
      <input type="hidden" id="rqemp" name="rqemp" value="{{ $rqemp }}"  class="form-control">
      <input type="text" value="{!! $emp->empcode ?? '' !!}"  class="form-control" readonly>
      <input type="text" value="{!! ($emp->name ?? '') . '/'. ($emp->thname ?? '') !!}"  class="form-control" readonly>
      <label  class="form-control btn-primary" for="rqmth">Month </label>
      <select id="rqmth" name="rqmth" class="form-control">
        @foreach($mths as $mth)
        <option value="{{ $mth->id }}"{!! $rqmth == $mth->id ? " SELECTED" : "" !!}>{{ $mth->name ?? '' }}</option>
        @endforeach
      </select>
    </div>
    <div class="float-right">
      <div class="form-inline">
        <a id="btnverify" class="btn btn-sm">Verify</a>
      </div>
    </div>
  </div>
  <table class="table table-sm table-hover">
    <thead>
      <tr align="right">
        <th>Date</th>
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
        @php ($cal = $wk->cal)
        @php ($wf = $emp->cm > 3000)
        @php ($holiday = ( ($wf ? $cal->wf : $cal->of) == 3683) )
        <tr align="right" data-id="{{ $wk->w_id }}" data-oid="{{ $wk->at_id }}"
        {!! ($holiday ? "class='bg-info'" : '') !!}>
          <td class="edate" data-value="{!! edate($wk->w_date,10) !!}">{!! edate($wk->w_date) !!}</td>
          <td class="small">{!! $wk->workorder . ($lvrq && $wk->w_lvid > 0 
            ? '/<a class="lvrq" data-id="' . $wk->w_lvid . '" title="' .  $lvrq->lv->name . '">'
            .  $lvrq->lv->code . "</a>" 
          : ($wk->w_lvid ? '/<span class="text-danger">'.$wk->w_lvid.'</span>' : '') ) !!}</td>
          <td>{!! edate($wk->tin,21) !!}</td>
          <td>{!! edate($wk->tout,21) !!}</td>
          <td>{!! edate($wk->tin2,21) !!}</td>
          <td>{!! edate($wk->tout2,21) !!}</td>
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
    $('#rqmth').on('change', function() {headrelocate();});
    $('.edate').on('click', function() {
      let date = $(this).attr('data-value');
      let grp = {{$emp->cm ?? 0}};
      let url ="{!! url('daily') !!}" + '/'+date+'/'+grp;
      window.location = url;
    });
    // Document.Ready()
  });

  function headrelocate() {
    let emp = $('#rqemp').val();
    let mth = $('#rqmth').val();
    let url ="{!! url('monthly') !!}" + '/'+mth+'/'+emp;
    window.location = url;
  }
</script>
@endsection

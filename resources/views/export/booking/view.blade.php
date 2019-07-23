@extends('layouts.app')

@section('content')
@php( $book = \App\Models\mkbooking::findornew($bookid) )
@if(!$bookid)
  @php( $book->bookdate = date('Y-m-d h:i:s') )
@endif
<div class="container" id="pagebooking">
  <div class="h3 title"><a href="{{ url('booking') }}">Booking ID : {{ $book->id }}</a></div>
  <form id="formbooking">
    <div class="row">
      <label class="col-1">Number</label>
      <input class="col-2 form-control" type="text" name="bookcode" value="{{ $book->code ?? '' }}">
      <label class="col-1">Date </label>
      <input class="col-2 form-control" type="text" name="bookdate" value="{{ edate($book->bookdate ?? 0, 22)  }}">

      <label class="col-1">Agent</label>
      @php( $fwders = App\Models\Dir::InGroup(array(546,540,542))->orderBy('name')->get() )
      <select name="agentid" id="selectagent" class="toggleselect form-control col-5">
        <option value=0>-Select Agent/Forwarder-</option>
        @foreach($fwders as $fw) 
          <option value="{{ $fw->id }}"{!! ($fw->id == $book->agentid ? "SELECTED" : '') !!}>{{ $fw->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="row">
      <label class="col-1">Feeder </label>
      <input class="col-3 form-control" type="text" name="feedname" value="{{ $book->feeder->name ?? ''}}" placeholder="from Loading Port">
      <label class="col-1">Voy </label>
      <input class="col-1 form-control" type="text" name="feedvoy" value="{{ $book->feedervoy ?? ''}}">
      <label class="col-1">Loading </label>
      <input class="col-2 form-control" type="text" name="portfromid" value="{{ $book->fromport->fullname ?? '' }}" title="{{ $book->fromport->sub ?? ''}}" placeholder="Loading Port">
      <label class="col-1">ETD  </label>
      <input class="col-2 form-control" type="text" name="etddate" value="{{ edate($book->etddate ?? 0) }}">
    </div>
    <div class="row">
      <label class="col-1">Carrier </label>
      <input class="col-3 form-control" type="text" value="{{ $book->carrier->name ?? ''}}" placeholder="for Transhipment">
      <label class="col-1">Voy </label>
      <input class="col-1 form-control" type="text" value="{{ $book->carriervoy ?? '' }}">
      <label class="col-1">Discharge </label>
      <input class="col-2 form-control" type="text" value="{{ $book->toport->fullname ?? '' }}" title="{{ $book->toport->sub ?? '' }}" placeholder="Discharging Port">
      <label class="col-1">ETA  </label>
      <input class="col-2 form-control" type="text" value="{{ edate($book->etadate ?? 0) }}">
    </div>
    <div class="row">
      <div class="col h3 title">Container Received</div>
      <div class="col h3 title">Container Return</div>
    </div>
    <div class="row">
      <div class="col-6">
        <div class="row">
          <span class="col-2">CY At </span>
          <input class="col-5 form-control" type="text" value="{{ $book->receivefrom ?? ''}}">
          <span class="col-2">On </span>
          <input class="col-3 form-control" type="text" value="{{ edate($book->receivedate ?? 0) }}">
        </div>
        <div class="row">
          <span class="col-2">Contact </span>
          <input class="col-5 form-control" type="text" value="{{ $book->receiveperson ?? '' }}">
          <span class="col-2">Remark  </span>
          <input class="col-3 form-control" type="text" value="{{ $book->receivememo ?? '' }}">
        </div>
      </div>
      <div class="col-6">
        <div class="row">
          <span class="col-2">Return at</span>
          <input class="col-5 form-control" type="text" value="{{ $book->returnto ?? ''}}">
          <span class="col-2">On </span>
          <input class="col-3 form-control" type="text" value="{{ edate($book->returndate ?? 0) }}">
        </div>
        <div class="row">
          <span class="col-2">Contact </span>
          <input class="col-5 form-control" type="text" value="{{ $book->returnperson ?? '' }}">
          <span class="col-2">Remark </span>
          <input class="col-3 form-control" type="text" value="{{ $book->returnmemo ?? '' }}">
        </div>
      </div>
    </div>

    <div class="row">
      <span class="col-1">Liner </span>
      <!-- <input class="col-8 form-control" type="text" value="{{ $book->liner->name ?? ''}}">  -->
      @php( $liners = App\Models\Dir::InGroup(array(546))->orderBy('name')->get() )
      <select name="agentid" id="selectagent" class="toggleselect form-control col-5">
        <option value=0>-Select Liner-</option>
        @foreach($liners as $fw) 
          <option value="{{ $fw->id }}"{!! ($fw->id == $book->linerid ? "SELECTED" : '') !!}>{{ $fw->name }}</option>
        @endforeach
      </select>

      <span class="col-1">Close at</span>
      <input class="col-2 form-control" type="text" value="{{ edate($book->closetime ?? 0,22) }}">
    </div>

    <h3 class="title">Charge Rate</h3>
    <div class="body col-6">
      <table class="table table-sm table-border">
        <tr class="text-center">
          <th width="20%">Size</th>
          <th width="20%">Normal</th>
          <th width="20%">H Type</th>
          <th width="20%">@Freight<br>(USD)</th>
          <th width="20%">@THC<br>(THB)</th>
        </tr>
        <tr class="text-right">
          <td>LCL (M<sup>3</sup>)</td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->qlcl ?? 0,0,0,3) }}"></td>
          <td>&nbsp;</td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->lclprice ?? 0) }}"></td>
          <td>&nbsp;</td>
        </tr>
        <tr class="text-right">
          <td>FCL 20'</td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->q20 ?? 0) }}"></td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->q20h ?? 0) }}"></td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->f20price ?? 0,0,0,2) }}"></td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->thc20price ?? 0,0,0,2) }}"></td>
        </tr>
        <tr class="text-right">
          <td>FCL 40'</td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->q40 ?? 0) }}"></td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->q40h ?? 0) }}"></td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->f40price ?? 0,0,0,2) }}"></td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->thc40price ?? 0,0,0,2) }}"></td>
        </tr>
        <tr class="text-right">
          <td colspan=4 class="text-center">B/L Charge</td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->docprice ?? 0,0,0,2) }}"></td>
        </tr>
      </table>
    </div>
  </form>
</div>
@endsection

@section('script')
<script>
  $(document).ready(function() {

  });
</script>
@endsection
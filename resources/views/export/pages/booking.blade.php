@php($book = $inv->booking)

<div class="container borderless rounded part" id="pagebooking">
  <form id="formbooking">
    <h3 class="title">Invoice No. {{ $inv->invfullnumber }}</h3>
    <div class="body">
      <div class="row">
        <span class="col-1">Book.Number </span>
        <input class="col-2 form-control" type="text" value="{{ $book->code ?? '' }}">
        <span class="col-1">Book.Date </span>
        <input class="col-2 form-control datetext" type="text" value="{{ edate($book->bookdate ?? '')  }}">
        <input class="col-2 form-control datebox" type="date" value="{{ edate($book->bookdate,7) ?? '' }}">
        <span class="col-1">Agent</span>
<!--   <input class="col-5 form-control" type="text" value="{{ $book->agent->name ?? ''}}"> --}}  
        <label class="col-1">Agent</label> -->
        @php( $fwders = App\Models\Dir::InGroup(array(546,540,542))->orderBy('name')->get() )
        <select name="agentid" id="selectagent" class="toggleselect form-control col-5">
          <option value=0>-Select Agent/Forwarder-</option>
          @foreach($fwders as $fw) 
            <option value="{{ $fw->id }}"{!! ($fw->id == $book->agentid ? "SELECTED" : '') !!}>{{ $fw->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="row">
        <span class="col-1">Feeder </span>
        <input class="col-3 form-control" name="feedername" vesselid="{{ $book->feederid ?? 0 }}" type="text" value="{{ $book->feeder->name ?? ''}}">
        <span class="col-1">Voy </span>
        <input class="col-1 form-control" name="feedervoy" type="text" value="{{ $book->feedervoy ?? ''}}">
        <span class="col-1">Loading </span>
        <input class="col-2 form-control" name="fromportname" portid="{{ $book->fromportid }}" type="text" value="{{ $book->fromport->fullname ?? '' }}" title="{{ $book->fromport->sub ?? ''}}">
        <span class="col-1">ETD  </span>
        <input class="col-2 form-control datetext" type="text" value="{{ edate($book->etddate ?? 0) }}">
        <input class="col-2 form-control datebox" name="etddate" type="date" name="etddate" value="{{ edate($book->etddate, 7) ?? '' }}">
      </div>
      <div class="row">
        <span class="col-1">Carrier </span>
        <input class="col-3 form-control" name="carriername" vesselid="{{ $book->carrierid ?? 0 }}" type="text" value="{{ $book->carrier->name ?? ''}}">
        <span class="col-1">Voy </span>
        <input class="col-1 form-control" name="carriervoy" type="text" value="{{ $book->carriervoy ?? '' }}">
        <span class="col-1">Discharge </span>
        <input class="col-2 form-control" name="toportname" type="text" value="{{ $book->toport->fullname ?? '' }}" title="{{ $book->toport->sub ?? '' }}">
        <span class="col-1">ETA  </span>
        <input class="col-2 form-control datetext" type="text" value="{{ edate($book->etadate ?? 0) }}">
        <input class="col-2 form-control datebox" name="etadate" type="date" value="{{ edate($book->etadate,7) ?? '' }}">
      </div>
      <div class="row">
        <div class="col h3 title">Container Received</div>
        <div class="col h3 title">Container Return</div>
      </div>
      <div class="body">
        <div class="row">
          <div class="col-6">
            <div class="row">
              <span class="col-2">CY At </span>
              <input class="col-5 form-control" type="text" name="receivefrom" value="{{ $book->receivefrom ?? ''}}">
              <span class="col-2">On </span>
              <input class="col-3 form-control datetext" type="text" value="{{ edate($book->receivedate ?? 0) }}">
              <input class="col-3 form-control datebox" name="receivedate" type="date" value="{{ edate($book->receivedate ?? 0) }}">
            </div>
            <div class="row">
              <span class="col-2">Contact </span>
              <input class="col-5 form-control" type="text" name="receiveperson" value="{{ $book->receiveperson ?? '' }}">
              <span class="col-2">Remark  </span>
              <input class="col-3 form-control" type="text" name="receivememo" value="{{ $book->receivememo ?? '' }}">
            </div>
          </div>
          <div class="col-6">
            <div class="row">
              <span class="col-2">Return At </span>
              <input class="col-5 form-control" type="text" name="returnto" value="{{ $book->returnto ?? ''}}">
              <span class="col-2">On </span>
              <input class="col-3 form-control datetext" type="text" value="{{ edate($book->returndate) ?? '' }}">
              <input class="col-3 form-control datebox" name="returndate" type="text" value="{{ edate($book->returndate,7) ?? '' }}">
            </div>
            <div class="row">
              <span class="col-2">Contact </span>
              <input class="col-5 form-control" type="text" name="returnperson" value="{{ $book->returnperson ?? '' }}">
              <span class="col-2">Remark </span>
              <input class="col-3 form-control" type="text" name="returnmemo" value="{{ $book->returnmemo ?? '' }}">
            </div>
          </div>
        </div>

        <div class="row">
          <span class="col-1">Liner </span>
<!--          <input class="col-8 form-control" type="text" value="{{ $book->liner->name ?? ''}}">  -->
          @php( $liners = App\Models\Dir::InGroup(array(546))->orderBy('name')->get() )
          <select name="agentid" id="selectagent" class="toggleselect form-control col-5">
            <option value=0>-Select Liner-</option>
            @foreach($liners as $fw) 
              <option value="{{ $fw->id }}"{!! ($fw->id == $book->linerid ? "SELECTED" : '') !!}>{{ $fw->name }}</option>
            @endforeach
          </select>

          <span class="col-1">Closing at  </span>
          <input class="col-2 form-control datetext" type="text" value="{{ edate($book->closetime,1) ?? '' }}">
          <input class="col-2 form-control datebox" dateformat="1" type="datetime" name="closedtime" value="{{ edate($book->closetime,8) ?? '' }}" placeholder="m/d/y h:m">
        </div>
      </div>
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
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->lclprice ?? 0) }}"{{ $book->qlcl ? '' : " READONLY"}}></td>
          <td>&nbsp;</td>
        </tr>
        <tr class="text-right">
          <td>FCL 20'</td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->q20 ?? 0) }}"></td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->q20h ?? 0) }}"></td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->f20price ?? 0,0,0,2) }}"{{ $book->q20+$book->q20h ? '' : " READONLY" }}></td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->thc20price ?? 0,0,0,2) }}"></td>
        </tr>
        <tr class="text-right">
          <td>FCL 40'</td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->q40 ?? 0) }}"></td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->q40h ?? 0) }}"></td>
          <td><input type="text" class="form-control text-right" value="{{ fnum($book->f40price ?? 0,0,0,2) }}"{{ $book->q40+$book->q40h ? '' : " READONLY"}}></td>
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


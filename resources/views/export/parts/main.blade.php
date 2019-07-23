<button class="btn btn-secondary col mb-2 text-left text-white" type="button" data-toggle="collapse"  data-target="#partmain-div" aria-expanded="true" aria-controls="partmain-div">
    Shipment Data
</button>

<div id="partmain-div" class="col collapse show">
  <div class="form-group row">
    <label for="invnum" class="col-sm-2 col-form-label">Invoice</label>
    <div class="col-sm-5">
      <input class="form-control" type="text" name="invnum" value="{{ $inv->invfullnumber }}">
    </div>

    <label for="invdate" class="col-sm-2 col-form-label">Date</label>
    <div class="col-sm-3">
      <input class="form-control" type="text" name="invdate" value="{{ edate($inv->invdate) }}">
    </div>
  </div>

  <div class="form-group row">
    <label for="ponum" class="col-sm-2 col-form-label">Under P/O</label>
    <div class="col-sm-5">
      <input class="form-control" type="text" name="ponum" value="{{ $inv->ponum }}">  
    </div>
    <label for="point.code" class="col-sm-2 col-form-label">Point ID</label>
    <div class="col-sm-3" >
      <input class="form-control" type="text" id="pointid" data-id="{{ $point->id }}" name="point.code" value="{{ $point->code }}">
    </div>
  </div>

  <div class="form-group row">
    <label for="credit.code" class="col-sm-2 col-form-label">Credit</label>
    <div class="col-sm-5">
      <input class="form-control" type="text" id="creditcode" name="credit.code" value="{{ $credit->code ?? '' }}">  
    </div>
    <label for="credit.opendate" class="col-sm-2 col-form-label">Date</label>
    <div class="col-sm-3">
      <input class="form-control" type="text" name="credit.opendate" value="{{ edate($credit->opendate ?? 0) }}"></td>
    </div>
  </div>

  <div class="form-group row">
    <label for="payname" class="col-sm-2 col-form-label">Payment</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="payname" value="{{ $payterm->name }}">
    </div>
  </div>

</div>

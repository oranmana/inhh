<button class="btn btn-secondary col mb-2 text-left text-white " type="button" data-toggle="collapse" data-target="#partprofile-div" aria-expanded="true" aria-controls="partprofile-div">
    Profile
</button>

<div id="partprofile-div" class="col collapse show">

  <div class="form-group row">
    <label for="pic.nm" class="col-sm-2 col-form-label">PIC</label>
    <div class="col-sm-5">
      <input class="form-control" type="text" name="pic.nm" value="{{ $pic->nm }}">
    </div>
    <label for="inv.statename" class="col-sm-2 col-form-label">Status</label>
    <div class="col-sm-3">
      <input class="form-control" type="text" name="inv.statename" value="{{ $inv->statename }}">
    </div>
  </div>

  <div class="form-group row">
    <label for="customer.name" class="col-sm-2 col-form-label">A/R</label>
    <div class="col-sm-5">
      <input class="form-control" type="text" name="customer.name" value="{{ $customer->name }}">
    </div>
    <label for="point.code" class="col-sm-2 col-form-label">Pay Code</label>
    <div class="col-sm-3">
      <input class="form-control" type="text" name="point.code" value="{{ $point->paycode }}">
    </div>
  </div>

  <div class="form-group row">
    <label for="inv.billnum" class="col-sm-2 col-form-label">Billing No.</label>
    <div class="col-sm-5">
      <input class="form-control" type="text" name="inv.billnum" value="{{ $inv->billnum }}">
    </div>
    <label for="inv.billdate" class="col-sm-2 col-form-label">Date</label>
    <div class="col-sm-3">
      <input class="form-control" type="text" name="inv.billdate" value="{!! (strlen($inv->billnum) ? edate($inv->bldate) : '') !!}">
    </div>
  </div>

</div>

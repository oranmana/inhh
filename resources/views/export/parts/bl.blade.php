<button class="btn btn-secondary col mb-2 text-left text-white" type="button" data-toggle="collapse" data-target="#partbl-div" aria-expanded="true" aria-controls="partbl-div">
    B/L Reference
</button>

<div id="partbl-div" class="col collapse show">

  <div class="form-group row">
    <label for="bl.num" class="col-sm-2 col-form-label">B/L No.</label>
    <div class="col-sm-5">
      <input class="form-control" type="text" name="bl.num" value="{{ $inv->blnum ?? ''}}">
    </div>
    <label for="bl.onboard" class="col-sm-2 col-form-label">On Board</label>
    <div class="col-sm-3">
      <input class="form-control" type="text" name="bl.onboard" value="{{ edate($book->etddate ?? 0) }}">
    </div>
  </div>

  <div class="form-group row">
    <label for="bl.poc" class="col-sm-4 col-form-label">Port of Discharge</label>
    <div class="col-sm-8">
      <input class="form-control" type="text" name="bl.poc" value="{{ $book->fromport->fullname ?? ''}}">
    </div>
  </div>

  <div class="form-group row">
    <label for="bl.pod" class="col-sm-4 col-form-label">Port of Delivery</label>
    <div class="col-sm-8">
      <input class="form-control" type="text" name="bl.pod" value="{{ $credit->placeofdelivery ?? ''}}">
    </div>
  </div>

</div>

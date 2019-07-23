
<button class="btn btn-secondary col mb-2 text-left text-white" type="button" data-toggle="collapse" data-target="#partcustoms-div" aria-expanded="true" aria-controls="partcustoms-div">
    Customs Data
</button>

<div id="partcustoms-div" class="col collapse show">

  <div class="form-group row">
    <label for="inv.broker" class="col-sm-2 col-form-label">Broker</label>
    <div class="col-sm-5">
      <input class="form-control" type="text" name="inv.broker" value="{{ ($inv->broker->name ?? '') }}">
    </div>

    <label for="mode" class="col-sm-2 col-form-label">Modal</label>
    <div class="col-sm-3">
      <input class="form-control" type="text" name="mode" value="{{ $inv->mode }}" placeholder="By Sea">  
    </div>

  </div>

  <div class="form-group row">
    <label for="custom.blnum" class="col-sm-2 col-form-label">Customs</label>
    <div class="col-sm-5">
      <input class="form-control" type="text" name="custom.blnum" value="{{ $inv->blnum }}">
    </div>
    <label for="custom.port" class="col-sm-2 col-form-label">Load Port</label>
    <div class="col-sm-3">
      <input class="form-control" type="text" name="custom.port" value="{{ $custom->exportport->name ?? '' }}">  
    </div>
  </div>

  <div class="form-group row">
    <label for="custom.num" class="col-sm-2 col-form-label">Entry No.</label>
    <div class="col-sm-5">
      <input class="form-control" type="text" name="custom.num" value="{{ $custom->exportnum ?? '' }}">
    </div>
    <label for="customs.date" class="col-sm-2 col-form-label">Date</label>
    <div class="col-sm-3">
      <input class="form-control" type="text" name="custom.date" value="{{ edate($custom->entrydate ?? 0 ) }}">  
    </div>
  </div>

  <div class="form-group row">
    <label for="custom.inspecton" class="col-sm-2 col-form-label">Inspected</label>
    <div class="col-sm-5">
      <input class="form-control" type="text" name="custom.inspecton" value="{{ edate($custom->date03 ?? 0) }}">
    </div>
    <label for="custom.date04" class="col-sm-2 col-form-label">04 Date</label>
    <div class="col-sm-3">
      <input class="form-control" type="text" name="custom.date04" value="{{ edate($custom->date04 ?? 0) }}">  
    </div>
  </div>

</div>

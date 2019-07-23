<button class="btn btn-secondary col mb-2 text-left text-white"  type="button" data-toggle="collapse" data-target="#partamount-div" aria-expanded="true" aria-controls="partamount-div">
    Invoice Amount
</button>

<div id="partamount-div" class="col collapse show">

  <div class="form-group row justify-content-start">
    <label for="inv.currency" class="col-sm-3 col-form-label">Invoice Amount</label>
    <div class="col-sm-2">
      <input class="form-control" type="text" name="inv.currency" value="{{ $currency->code }}"> 
    </div>
    <div class="col-sm-3">
      <input class="form-control text-right" type="text" name="inv.amount" value="{{ fnum($inv->amt,2) }}">
    </div>
    <div class="col-sm-2">
      <input class="form-control" type="text" value="{{ $priceterm->name }}"> 
    </div>
  </div>

  @if ($priceterm->code > 1)
  <div class="form-group row justify-content-start">
    <label for="inv.currencyII" class="col-sm-3 col-form-label">Freight</label>
    <div class="col-sm-2">
      <input class="form-control" type="text" name="inv.currencyII" value="{{ $currency->code }}" readonly>
    </div>
    <div class="col-sm-3">
      <input class="form-control text-right" type="text" value="{{ fnum($inv->freightamt,2) }}">
    </div>
  </div>
  @endif

  @if ($priceterm->code > 2)
  <div class="form-group row justify-content-start">
    <label for="inv.currencyIII" class="col-sm-3 col-form-label">Insurance</label>
    <div class="col-sm-2">
      <input class="form-control" type="text" name="inv.currencyIII" value="{{ $currency->code }}" readonly>
    </div>
    <div class="col-sm-3">
      <input class="form-control text-right" type="text" value="{{ fnum($inv->insureamt,2) }}">
    </div>
  </div>
  @endif

  @if ($priceterm->code > 1)
  <div class="form-group row justify-content-start">
    <label for="inv.currencyIV" class="col-sm-3 col-form-label">FOB Amount</label>
    <div class="col-sm-2">
      <input class="form-control" type="text" name="inv.currencyIV" value="{{ $currency->code }}" readonly>
    </div>
    <div class="col-sm-3">
      <input class="form-control text-right" type="text" value="{{ fnum($inv->fob,2) }}">
    </div>
  </div>
  @endif

</div>

<div class="container">
  @php ( $item = \App\Models\mkpackinglist::find($packid) )
  @php ( $amt = round($item->item->ASRPackSize * $item->packquantity / 1000 * $item->unitprice,2) )
  @php ( $usd = $item->inv->currency->code )
  <form id="packingitem" method="GET" action="{{ url('/export/itemsave') }}">
    <input type="hidden" name="invid" value="{{ $invid ?? 0 }}">
    <input type="hidden" name="packid" value="{{ $item->id ?? 0 }}">
    <div class="row">
      <label>Product Code</label>
      <input type='text' class="form-control" name="itemcode" value="{!! $item->item->itm_code ?? '' !!}">
    </div>
    <div class="row">
      <label>Product Name</label>
      <input type='text' class="form-control" value="{!! $item->sapitemid ? $item->item->name : '' !!}" readonly>
    </div>
    <div class="row">
      <label>Pack Size</label>
      <input type='text' class="form-control" value="{!! $item->sapitemid ? $item->netkgs . ' Kgs' : '' !!}" readonly>
    </div>
    <div class="row">
      <label>Number of Packages {!! $item->sapitemid ? ' ('.$item->item->ASRPackUnit .')': '' !!} </label>
      <input type='number' class="form-control" name="packqty" value="{!! $item->sapitemid ? $item->packquantity : '' !!}">
    </div>
    <div class="row">
      <label>Quantity</label>
      <input type='text' class="form-control" value="{!! $item->sapitemid ? ($item->item->ASRPackSize * $item->packquantity / 1000) . ' M/T'  : '' !!}" readonly>
    </div>
    <div class="row">
      <label>Unit Price ({{ $usd }}/MT)</label>
      <input type='number' class="form-control" name="unitprc" value="{!! $item->sapitemid ? $item->unitprice : '' !!}">
    </div>
    <div class="row">
      <label>Amount</label>
      <input type='text'  class="form-control" value="{!! $amt ? $usd.fnum($amt, 2) : '' !!}" readonly>
    </div>
  </form>
</div>
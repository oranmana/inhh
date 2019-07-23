@php( $arid = $inv->dirid)
@php( $credits = App\Models\mkcredit::OfCustomer( $arid )->Valid()->orderBy('opendate','desc') )
<form class="borderless rounded part" id="partcredit">

<div class="form-inline">
  <div class="h3 title mr-2"> Credit No. </div>  
  <select name="cr.code" id="creditid"  origin="{{ $inv->creditid }}" arid="{{ $arid }}" class="h3 form-control w-25" required>
    <option value=0>-Select-</option>
    <option value=-1>-Duplicate-</option>
    @foreach($credits->get() as $crd)
    <option value="{{ $crd->id }}" title="{!! date('d-M-Y', strtotime($crd->opendate)) . ' ['. $crd->credittype->name . ($crd->creditdays>0 ? ' ' . $crd->creditdays . ' Days' : ' At Sight') . ']' !!}"
      {!! ($crd->id == $inv->creditid ? " SELECTED" : '' ) !!}>
      {{ $crd->code }} </option>
    @endforeach
  </select>
  <div>{{ $inv->credit->code }}</div>
</div>

  <div class="body">
    <div class="row">
      <div class="col-2">
        <label>Type </label>
        <input name="cr.type" class="form-control" type="text" value="{{ $cr->credittype->name ?? ''}}">
      </div>
      <div class="col-2">
        <label>Issued On</label>
        <input name="cr.opendate" class="form-control" type="text" value="{{ edate($cr->opendate) ?? '' }}">
      </div>
      <div class="col-2">
        <label title="{{ $cr->id .'-'. $cr->opendate }}">Limit Amount</label>
        <input name="cr.amount" class="form-control  text-right" type="text" value="{{ fnum($cr->amount,2) ?? 0 }}"> 
      </div>
      <div class="col-6">
        <label>Customer </label>
        <input class="col form-control" type="text" value="{{ $cr->dir->name ?? '' }}">
      </div>
    </div>

    <div class="row">
      <div class="col-6">
        <label>Applicant</label>
        <textarea name="applicant" class="form-control" rows="5">{{ $cr->applicant ?? ''}}</textarea>
      </div>
      <div class="col-6">
        <label>Consignee</label>
        <textarea name="cr.consignee" class="form-control" rows="5">{{ $cr->consignee ?? ''}}</textarea>
      </div>     
    </div>

    <div class="row">
      <div class="col-6">
        <label>Beneficiary</label>
        <textarea name="cr.beneficiary" class="form-control" rows="5">{{ $cr->beneficiary ?? ''}}</textarea>
      </div>
      <div class="col-6 form-group">
        <label>Notify Party</label>
        <textarea name="cr.notify" class="form-control" rows="5">{{ $cr->notify ?? ''}}</textarea>
      </div>
    </div>

    <div class="row">
      <div class="col-4">
        <label>Shipping Marks</label>
        <textarea name="cr.mark" class="form-control" rows="10">{{ $cr->shippingmark ?? ''}}</textarea>
      </div>
      <div class="col-8">
        <label>Description</label>
        <textarea name="cr.des" class="form-control" rows="10">{{ $cr->description ?? '' }}</textarea>
      </div>
    </div>

    <div class="row col">
      <label>Shipping Document Remark</label>
      <textarea name="cr.rem" class="form-control" rows="10">{{ $cr->remark ?? '' }}</textarea>
    </div>
  </div>
  @if( ($cr->credittype->code ?? 0) > 1)
  <div class="footer col">
    <div class="h3">Bank Negotiation</div>
    <div>
      <div class="row">
        <div class="col-8">
          <span>Drawee</span>
          <textarea class="form-control" rows="3">{{ $cr->drawee }}</textarea>
        </div>
        <div class="col-4">
          <span>Credit (Days)</span>
          <input class="form-control" type="text" value="{{ $cr->creditdays }}">
          <span>Number of B/L Copies</span>
          <input class="form-control" type="text" value="{{ $cr->blnum }}">
        </div>
      </div>
      <div class="row">
        <div class="col-8">
          <span>Reimbursing Bank</span>
          <textarea class="form-control" rows="5">{{ $cr->bank }}</textarea>
        </div>
        <div class="col-4">
          <span>Loading Port</span>
          <input class="form-control" type="text" value="{{ $cr->fromport }}">
          <span>Port of Discharge</span>
          <input class="form-control" type="text" value="{{ $cr->toport }}">
          <span>Port of Delivery</span>
          <input class="form-control" type="text" value="{{ $cr->toport }}">
        </div>
      </div>
      <div class="row">
        <div class="col-6">Beneficiary Certificate</div>
        <div class="col-6">Certificate of Analysis</div>
      </div>
      <div class="row">
        <textarea class="col-6 form-control" rows="10">{{ $cr->certbeneficiary }}</textarea>
        <textarea class="col-6 form-control" rows="10">{{ $cr->certcoa }}</textarea>
      </div>
    </div>
  </div>
  @endif
</form>


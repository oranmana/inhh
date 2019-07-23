@extends('export.shipdoc.dochead')

@section('content')
  @php ($packs = $inv->items->sortBy('sapitemid')->sortBy('unitprice') )
  @php ($cr = $inv->credit)
  @php ($bk = $inv->booking)
  @php ($pricetermname = $inv->priceterm->name)
  @php ($priceterm = $pricetermname . " " . $cr->toport)
  <div class="container">
    <div class="row">
      <div class="col-7 doctitle">Commercial Invoice</div>
      <div class="col-5 docsubtitle">
        <div class=" float-left">No. {{ $inv->InvFullNumber }}</div>
        <div class="float-right">Date : {!! edate($inv->invdate) !!}</div>
      </div>
    </div>

    <div class="row label">For the risks of :</div>
    <div class="row"><pre>{{ $cr->applicant }}</pre></div>

    <div class="row">
      <div class="col-2 text-right label">Under :</div>
      <div class="col-10 clause">{!! $cr->credittype->name . ' No. ' . $cr->code . ' Dated ' . edate($cr->opendate) !!}</div>
    </div>

    <div class="row">
      <div class="col-2 text-right label">Reference :</div>
      <div class="col-10 clause">P/O No. {!! $inv->ponum !!}</div>
    </div>

    <div class="row">
      <div class="col-2 text-right label">S/O No. :</div>
      <div class="col-10 clause">{!! strtoupper($inv->sonum) !!}</div>
    </div>

    <div class="row">
      <div class="col-2 text-right label">Shipment :</div>
      <div class="col-10 clause">{!! strtoupper('FROM ' . $cr->fromport . ' TO ' . $cr->toport) !!}</div>
    </div>

    <div class="row">
      <div class="col-2 text-right label">Per :</div>
      <div class="col-10 clause">{!! strtoupper($bk->feedername . " on or about " . edate($bk->etddate) ) !!}</div>
    </div>

    <div class="row">
      <div class="col-2 text-right label">Price Term :</div>
      <div class="col-10 clause">{!! strtoupper($priceterm) . " (" . $inv->payterm->name . ")" !!}</div>
    </div>

  </div>
  <table class="table table-sm table-borderless tabledetails">
    <thead>
      <tr class="text-center row detailhead">
        <th class="col-1">Quantity</th>
        <th class="col-1">Packages</th>
        <th class="col-6">Description</th>
        <th class="col-2">Unit Price</th>
        <th class="col-2">Amount</th>
      </tr>
    </thead>
  @php($customsproduct = '')
  @php($saleamounts = 0)
  <tr class="row">
        <td colspan=2 class="col-2">&nbsp;</td>
        <td>{!! "<i>'No Brand'</i>" !!}
      </tr>
  
  @foreach($packs as $pack)
    @if($pack->CustomsProductName != $customsproduct)  
      <tr class="row">
        <td colspan=2 class="col-2">&nbsp;</td>
        <td>{!! '<b><u>' . $pack->CustomsProductName . '</u></b>' !!}
      </tr>
      @php($customsproduct = $pack->CustomsProductName)
    @endif

    @php($uom = $pack->item->uom )
    @php($saleamt = $pack->salequantity * $pack->unitprice)
    <tr class="text-right row">
      <td class="col-1">{!! fnum($pack->salequantity,3) .' '.$uom->code !!}</td>
      <td class="col-1">{{ fnum($pack->packquantity) . ' ' . $pack->packname }}</td>
      <td class="col-6 text-left">{{ $pack->item->itm_p2 }}</td>
      <td class="col-2">{!! $inv->currency->code .fnum($pack->unitprice,2) . '/'. $uom->name !!}</td>
      <td class="col-2">{!! $inv->currency->code . fnum($saleamt,2) !!}</td>
    </tr>
    @php($saleamounts += $saleamt)
  @endforeach
  @php($fobamounts = $saleamounts)
  <tr class="text-right detailtotal row">
    <td class="col-1">{!! fnum($packs->sum('salequantity'),3) .' '.$uom->code !!}</td>
    <td class="col-1">{{ fnum($packs->sum('packquantity')) . ' ' . $pack->packname }}</td>
    <td class="col-6 text-center">TOTAL</td>
    <td class="col-2 text-right">{!! $priceterm  !!}</td>
    <td class="col-2">{!! $inv->currency->code . fnum($saleamounts,2) !!}</td>
  </tr>
</table>
<table class="table table-sm table-borderless tabledetails">
  <tr>
    <td id="remark">
      @if(strlen($inv->remark))
      <div class="label">Remarks :</div>
      <pre>{{ $inv->remark }}</pre>
      @endif
    </td>
    @if( $pricetermname !=  'FOB' )
    <td>
      <table class="table table-sm table-borderless">
      @if($inv->NeedFreight )
        @php( $freight = $inv->freightamount)
        @php( $pfreight = $packs->sum('freightamount') )
        <tr class="row text-right">
          <td class="col-10 text-right">Freight Charges : </td>
          <td class="col-2">{!! ($freight ? $inv->currency->code : '') .fnum($freight,2) !!}</td>
        </tr>
        @php($fobamounts -= $freight)
      @endif
      @if($inv->NeedInsure )
        @php($premium = $inv->insureamount)
        @php($ppremium = $packs->sum('insureamount') )
        <tr class="row text-right">
          <td class="col-10">Marine Insurance : </td>
          <td class="col-2">{!! ($premium ? $inv->currency->code : '') .fnum($premium,2) !!}</td>
        </tr>
        @php($fobamounts -= $premium)
      @endif
      @if( $pricetermname !=  'FOB' )
        <tr class="row text-right">
          <td class="col-10">FOB {{ $cr->fromport }} : </td>
          <td class="col-2">{!! $inv->currency->code .fnum($fobamounts,2) !!}</td>
        </tr>
      @endif
      </table>
    </td>
    @endif
  </tr>
</table>
@endsection
{{-- input customerid, countryid, priceid, payid --}}

@php( $points = App\Models\mkpoint::ofASR()->orderBy('dirid')->orderBy('portid')->orderBy('pricetermid')->orderBy('paymentid') )
@if($customerid)
  @php ($points->ofDir($customerid) )
@endif
@if($countryid)
  @php ($points->ofCountry($countryid) )
@endif
@if($priceid)
  @php ($points->ofPrice($priceid) )
@endif
@if($payid)
  @php ($points->ofPay($payid) )
@endif
{{-- <tr><td colsapn=5>{{ $customerid .'-'. $countryid .'-'.$priceid.'-'.$payid }}</td></tr> --}}
@php( $pts = $points )
@foreach($pts->get() as $pt)
  <tr class="pointlist" data-id="{{ $pt->id }}">
    <td>{{ $pt->code ?? ''}}</td>
    <td>{{ strlen($pt->dir->nm) ? $pt->dir->nm : $pt->dir->name}}</td>
    <td>{{ strtoupper($pt->port->parent->name) }}</td>
    <td>{{ ucfirst(strtolower($pt->port->name)) ?? ''}}</td>
    <td>{{ $pt->priceterm->name ?? ''}}</td>
    <td>{{ $pt->payterm->code ?? ''}}</td>
  </tr>
@endforeach



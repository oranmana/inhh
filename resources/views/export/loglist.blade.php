<!-- $yymm required -- $yymm, $dom --> 
@php($invs = \App\Models\mkinvoice::OfVersion()->OfYM($yymm)->OfDom($domid)->orderByRaw("invnum > 
 '' desc")->orderBy('invnum')->orderBy('bldate')->orderBy('state')->get() )

@foreach($invs as $inv)
  @php( $customer = $inv->point->dir ?? 0 )
  <tr data-id="{{ $inv->id }}" class="datarow" >
    <td nowrap>{{ $inv->invnumber }}</td>
    <td nowrap>{{ $inv->sonum }}</td>
    <td nowrap>{{ $inv->ponum }}</td>
    <td>{!! $inv->point->dir->nm ?? '' !!}</td>
    <td>{!! $inv->point->port->name  ?? '' !!}</td>
    <td>{!! $inv->priceterm->name ?? '' !!}</td>
    <td align="right">{!! fnum($inv->all20,0,0,1) !!}</td>
    <td align="right">{!! fnum($inv->all40,0,0,1) !!}</td>
    <td align="right">{!! $inv->currency->code !!}</td>
    <td align="right">{!! fnum($inv->amt,2) !!}</td>
    <td nowrap>{{ edate($inv->bldate) }}</td>
    <td>{!! $inv->pic->emp->nm !!}</td>
    <td>{!! $inv->statename !!}</td>
  </tr>
@endforeach
  <tr class="small bg-info" >
    <td colspan=6 align="center">TOTAL</td>
    <td align="right">{!! fnum($invs->sum('q20')+$invs->sum('q20h'),0,0,1) !!}</td>
    <td align="right">{!! fnum($invs->sum('q40')+$invs->sum('q40h'),0,0,1) !!}</td>
    <td align="right">&nbsp;</td>
    <td align="right">{!! fnum($invs->sum('amt'),2) !!}</td>
    <td colspan=3>&nbsp;</td>
  </tr>

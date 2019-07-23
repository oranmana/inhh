<!-- $yymm required -- $yymm, $dom --> 
@php($invs = \App\Models\mkinvoice::OfVersion()->OfYM($yymm)->OfDom($domid)->orderByRaw("invnum > 
 '' desc")->orderBy('invnum')->orderBy('bldate')->orderBy('state')->get() )

@foreach($invs as $inv)
  @php( $customer = $inv->point->dir ?? 0 )
  @php( $book = $inv->booking ?? 0 )
  <tr data-id="{{ $inv->id }}" class="datarow" >
    <td nowrap>{{ $inv->invnumber }}</td>
    <!-- <td nowrap>{{ $inv->sonum }}</td>
    <td nowrap>{{ $inv->ponum }}</td> -->
    <td>{!! $inv->point->dir->nm ?? '' !!}</td>
    <!-- <td>{!! $inv->priceterm->name ?? '' !!}</td> -->
    <td align="right">{!! fnum($book->all20 ?? 0,0,0,1) !!}</td>
    <td align="right">{!! fnum($book->all40 ?? 0,0,0,1) !!}</td>
    <!-- <td align="right">{!! $inv->currency->code !!}</td>
    <td align="right">{!! fnum($inv->amt,2) !!}</td> -->
    <td class="small">{{ $book->code ?? ''}}</td>
    <td class="small">{{ $book->FeederName ?? '' }}</td>
    <td nowrap>{{ edate($book->closetime ?? 0, 21) }}</td>
    <td nowrap>{{ edate($book->etddate ?? 0,12) }}</td>
    <td nowrap>{{ edate($book->etadate ?? 0,12) }}</td>
    <td>{!! $book->toport->name  ?? '' !!}</td>
    <td>{{ $book->receivefrom ?? '' }}</td>
    <td>{{ $book->returnto ?? '' }}</td>
    <!-- <td>{!! $inv->pic->emp->nm !!}</td>
    <td>{!! $inv->statename !!}</td> -->
  </tr>
@endforeach
  <tr class="small bg-info" >
    <td colspan=3 align="center">TOTAL</td>
    <td align="right">{!! fnum($invs->sum('q20')+$invs->sum('q20h'),0,0,1) !!}</td>
    <td align="right">{!! fnum($invs->sum('q40')+$invs->sum('q40h'),0,0,1) !!}</td>
    <td align="right">&nbsp;</td>
    <!-- <td align="right">{!! fnum($invs->sum('amt'),2) !!}</td> -->
    <!-- <td colspan=3>&nbsp;</td> -->
  </tr>

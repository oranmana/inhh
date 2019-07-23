<table class="table table-sm table-hover">
<thead>
  <tr class="text-center bg-primary">
    <th>ID</th>
    <th>Date</th>
    <th>Item</th>
    <th>Description</th>
    <th>Amount</th>
    <th>PIC</th>
    <th>Org</th>
    <th>State</th>
    <th class="small">SAP ID</th>
  </tr>
</thead>
<tbody>
  @foreach($assets as $asset)
    @php( $jState = optional($asset)->OjState )
    <tr data-id="{{ optional($asset)->id }}" class="datarow small{!! strlen( (optional($asset)->sapcode ?? '')) ? " bg-success" : '' !!}" data-id="{{ optional($asset)->id ?? ''}}" 
    data-toggle="modal" data-target="#ModalLong">
      <td class="text-right">{{ optional($asset)->id ?? ''}}</td>
      <td nowrap>{{ edate(optional($asset)->indate)  ?? '' }}</td>
      <td>{{ optional($asset)->item->name  ?? '' }}</td>
      <td class="col-md-4">{!! optional($asset)->des 
        . ' ' .optional($asset)->brand 
        . ' ' .optional($asset)->model 
        . ' ' .optional($asset)->serial
        ?? '' 
      
      !!}</td>
      <td class="text-right">{{ fnum(optional($asset)->amount)  ?? '' }}</td>
      <td{!! (! empty($asset) ? " title='" . (optional($asset)->pic->name ?? '') . "'"  : '') !!}>
        {{ optional($asset)->pic->nm  ?? '' }}</td>
      <td{!! (! empty($asset) ? " title='" . (optional($asset)->org->name ?? '') . "'"  : '') !!}>
        {{ optional($asset)->org->code  ?? '' }}</td>
      <td><span class="badge{{ $jState ? ' badge-' . optional($jState)->ref : '' }}">{{ optional($jState)->name ?? ''}}</span></td>
      <td>{{ optional($asset)->sapcode ?? ''}}</td>
    </tr>
  @endforeach
  </tbody>
</table>

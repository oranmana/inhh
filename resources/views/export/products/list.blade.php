@php( $listitem = ($igroup == 2 ? \App\Models\sapitem::RM()
  : \App\Models\sapitem::where('itm_code','like', $igroup.'%')
 ) )
 @php( $lists = $listitem->orderBy('itm_code')->get() )
<table class="table table-small mt-2">
  <tr class="text-center">
    <th>BA</th>
    <th>Type</th>
    <th>VC</th>
    <th>Code</th>
    <th>Name</th>
    <th>Commercial Name</th>
    <th>UOM</th>
    <th>P1</th>
    <th>P2</th>
    <th>P3</th>

  </tr>
  @foreach($lists as $list)
  <tr class="item" data-id="{{ $list->itm_id }}" mat-type="{{ substr($list->itm_code,-1) }}"
  data-toggle="modal" data-target="#ModalLong">
    <td>{{ $list->itm_ba }}</td>
    <td>{{ $list->itm_type }}</td>
    <td>{{ $list->itm_vc }}</td>
    <td>{{ $list->itm_code }}</td>
    <td>{{ $list->itm_name }}</td>
    <td>{{ $list->itm_cname }}</td>
    <td>{{ $list->uom->name ?? '' }}</td>
    <td>{{ $list->itm_p1 }}
    <td>{{ $list->itm_p2 }}
    <td>{{ $list->itm_p3 }}
  </tr>
  @endforeach
</table>


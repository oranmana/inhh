@if($pp)
  @php( $docs = \App\Models\Doc::OfYear($year)->OfOrg($orgid)->OfType($typeid) 
    ->orderBy('indate','desc')->get()
  )
@else
  @php( $docs = \App\Models\Doc::OfMonth($year)->OfOrg($orgid)->OfType($typeid) 
    ->orderBy('indate','desc')->get()
  )
@endif
<div class="tbl-header">
  <table class="table table-sm">
    <thead>
      <tr class="bg-info">
        <td>No.</td>
        <td>Date</td>
        <td>Subject</td>
        <td>From</td>
        <td>To</td>
        <td>Ref</td>
        <td>State</td>
      </tr>
    </thead>
  </table>
</div>
<div class="tbl-content">
  <table class="table table-sm table-hover table-striped">
    <tbody>
      @foreach($docs as $doc)
      @php($files = count($doc->FilesUploaded) )
      <tr data-id="{{ $doc->id }}" class="docuploadarea">
        <td nowrap>{{ $doc->DocNum }}</td>
        <td nowrap>{{ edate($doc->indate) }}</td>
        <td title="{!! $files . '-' . ($doc->oid ?? 'A') !!}">{!! 
          "<a class='uploadeddoc" . ($files > 0 ? "'" : " text-info'") 
          . " data-toggle='modal' data-target='#ModalLong'>". $doc->name . "</a>" 
        !!}</td>
        <td>{!! ($doc->user ? $doc->user->name : $doc->docfrom) !!}</td>
        <td>{!! $doc->docto !!}</td>
        <td class=small>{!! ($doc->pp ? $doc->doctype->code : $doc->ref) !!}</td>
        <td>{!! $doc->state !!}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{-- $docs->links('pagination') --}}
</div>

@php( $lists = \App\Models\DocType::where('par', $par)->orderBy('num')->get() )
<table class="table table-small">
  <tr class="doctype" data-id="{{ $par }}" data-toggle="modal" data-target="#ModalLong">
    <td colspan=5 class="h3 bg-primary">{{ $lists->first()->parent->name }}</td>
  </tr>
  <tr class="text-center">
    <th>No.</th>
    <th>Code</th>
    <th>Name</th>
    <th colspan=2>Group</th>
  </tr>
  @foreach($lists as $list)
  @php( $tm = ($list->des ? 
    "<div class='badge badge-primary'>" 
    . str_replace(',',"</div> <div class='badge badge-primary mr-2'>", $list->des) 
    . "</div>" : '') )  
  <tr class="doctype" data-id="{{ $list->id }}" data-toggle="modal" data-target="#ModalLong">
    <td class="w-10 text-right">{{ $list->num }}.
    <td>{{ $list->code }}
    <td>{{ $list->name }}
    <td>{!! ( strlen($list->tname) ? $list->tname : ($list->par==3989 ? 'PP' : "<div class='badge badge-secondary'>Team</div>") ) !!}
    <td>{!! $tm !!}
  </tr>
  @endforeach
</table>


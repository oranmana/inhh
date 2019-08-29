@php($relatives = $emp->relatives)
<table class="table table-condensed table-bordered ">
  <thead>
    <tr class="table-info">
      <th>Relationship</th>
      <th>ID Card</th>
      <th>Name</th>
      <th>Tel</th>
      <th>Age</th>
      <th>Status</th>
    </tr>
  <thead>
  <tbody>
  @foreach ($relatives as $rel)
  <tr id="{!! $rel->did !!}">
    <td>{!! ($rel->family ? $rel->family->name : $rel->emprelative) !!}</td>
    <td>{{ $rel->code }}</td>
    <td>{!! $rel->name !!}</td>
    <td>{{ $rel->tel }}</td>
    <td>{!! ($rel->bdate ? age($rel->bdate) : '') !!}</td>
    <td>{{ ($rel->taxstate ? $rel->taxstate->name : $rel->empcat) }}</td>
  </tr>
  @endforeach
  </tbody>
</table>
@can('isHR')
<div><a>Add</a></div>
@endcan
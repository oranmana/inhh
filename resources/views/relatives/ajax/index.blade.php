This is ajax render
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
    <td>{{ $rel->rname }}</td>
    <td>{{ $rel->dcode }}</td>
    <td>{!! $rel->dsex . $rel->dname !!}</td>
    <td>{{ $rel->dtel }}</td>
    <td>{!! ($rel->dbdate ? age($rel->dbdate) : '') !!}</td>
    <td>{{ $rel->catname }}</td>
  </tr>
  @endforeach
  </tbody>
</table>

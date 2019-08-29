@php($educations = $emp->education)
<table class="table table-condensed table-bordered ">
  <thead>
    <tr class="table-info">
      <th>Degree</th>
      <th>Year</th>
      <th>Institution</th>
      <th>Location</th>
      <th>Grade</th>
      <th>Remark</th>
    </tr>
  <thead>
  <tbody>
  @foreach ($educations as $ed)
  <tr id="{!! $ed->did !!}">
    <td>{!! ($ed->EdLevel ? $ed->EdLevel->des : $ed->code) !!}</td>
    <td>{{ $ed->yr }}</td>
    <td>{!! $ed->name !!}</td>
    <td>{{ $ed->loc }}</td>
    <td align=right>{{ fnum($ed->grd,0,0,2) }}</td>
    <td>{{ $ed->rem }}</td>
  </tr>
  @endforeach
  </tbody>
</table>
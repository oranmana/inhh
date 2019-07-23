<div class="h3">Organization : <b><a id="profile" data-id="{{ $org->id }}" data-toggle="modal" data-target="#ModalLong">{{ '('.$org->code.') ' 
  . $org->fullname . ($org->tname ? ' / ' . $org->tname : '') }}</a></b></div>
<table class="table table-hover table-sm">
<thead>
  <tr class="bg-primary">
    <th>No.</th>
    <th>Position</th>
    <th>Employee</th>
    <th>Job Title</th>
    <th>Promoted On</th>
  </tr>
</thead>
@if($emps)
  @php($ln=1)
  @foreach( $emps as $emp )
    @php($promotion = $emp->promotion->first->toJson() )
  <tr>
    <td class="text-right">{!! $ln++ !!}. </td>
    <td>{{ $emp->pos->code ?? '' }}</td>
    <td>{{ $emp->name }}</td>
    <td>{{ $emp->curjob->name }}</td>
    <td>{{ edate($promotion->indate) }}</td>
  </tr>
  @endforeach
@endif
</table>
@php( $lvrqs = \App\Models\LeaveRq::UnderEmp($rqemp)->orderBy )
<div class="tbl-header">
  <table class="table table-sm">
    <thead>
      <tr class="text-center header">
        <th class="w-7">ID</th>                
        <th>Employee</th>                
        <th>Type</th>
        <th>Leave Date</th>
        <th>Verified</th>                
        <th>Approved</th>                
      </tr>
    </thead>
  <!-- </table>
</div>

<div class="tbl-content">
  <table class="table table-sm"> -->
    <tbody>
    @if($leaves)
      @php($ln=1)
      @foreach($leaves as $lv)

        <tr >
          <td class="text-right">#{{ $ln++ }}) {{ $lv->id }} </td>
          <td><a emp-id="{{ $lv->emp->id }}">{{ $lv->emp->fullname }}</a></td>
          <td><a class="{{ in_array($lv->lv->chparent->id, array(9196,9193)) ? 'outdoorrequest' : 'leaverequest' }}" lv-code="{{ $lv->lvid }}">{{ $lv->lv->name }}</a></td>
          <td>{{ date('d-M-Y', $lv->fromdate) }}
          {!! ($lv->fromdate == $lv->tilldate ? '' : '- '.date('d-M-Y', $lv->tilldate) )!!}</td>
          <td>{!! $lv->verified_by ? $lv->verifier->username : '-' !!}</td>
          <td>{!! $lv->approved_by ? $lv->approver->username : 'X' !!}</td>
        </tr>
      @endforeach
    @else
      <tr><td class="h3 text-center">No Record</td></tr>
    @endif
    </thead>
  </tbody>
</div>


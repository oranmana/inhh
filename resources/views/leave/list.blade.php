@php ( $leaves=\App\Models\LeaveRq::OfYear( $yr )->UnderEmp( $emp->id )->orderBy('fdate','desc')->get() )
@php( $uempid = auth()->user()->empid )

<!-- <div class="tbl-header"> -->
  <table class="table table-sm">
    <thead>
      <tr class="text-center header">
        <th class="w-7">ID {{ $leaves->count() }} </th>                
        <th>Employee</th>                
        <th>Type</th>
        <th>Leave Date</th>
        <th class="text-center req">Requested</th>                
        <th class="text-center">Verified</th>                
        <th class="text-center app">Approved</th>                
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
      @php( $rq = $lv->rqNum )
      @php( $ap = $lv->apNum )
      @php( $canv = $lv->verifyby->where('id', $uempid)->count() )
      @php( $canp = $lv->approveby->where('id', $uempid)->count() )
      @can('isHRTM')
        @php( $canp = 9 )
      @endcan
      <tr data-id="{{ $lv->id }}" class="rq{{ $rq}} ap{{ $ap }}">
        <td class="text-right" title="{{ $ln++ }}"><a class="lvcard" data-toggle="modal" data-target="#ModalLong">{{ $lv->id ?? 0 }}</a></td>
        <td><a emp-id="{{ $lv->emp->id ?? 0 }}">{!! $lv->emp->fullname . ' [' . $lv->emp->pos->code . ']' !!}</a></td>
        <td><a lv-code="{{ $lv->lvid ?? 0 }}">{{ $lv->lv->name ?? '' }}</a></td>
        <td>{{ date('d-M', $lv->fromdate ?? 0 ) }}
        {!! ($lv->fromdate == $lv->tilldate ? '' : '- '.date('d-M', $lv->tilldate) )!!}</td>
        <td class="text-center" {!! $lv->requested_by ? " title='By " . ($lv->requestor->username ?? '') . "'" : '' !!}">
          {!! $lv->requested_at ? date('d-M/H:i', strtotime($lv->requested_at) ) : '-' !!}
        </td>
        <td class="text-center" {!! $lv->verified_by ? " title='By " . $lv->verifier->username . "'" : '' !!}">
        @if(($lv->IsPending() || ! $lv->verified_at) && (! $lv->approved_at) && $canv)
          <button class='btn-verify btn btn-warning' mode='1'>
            Verify
          </button>
        @else
          {!! $lv->verified_at ? date('d-M/H:i', strtotime($lv->verified_at) ) : '-' !!}
        @endif
        </td>
        <td class="text-center" {!! $lv->approved_by ? " title='By " . ($lv->approver->username ?? '') . "'" : '' !!}">
        @if( ($lv->IsPending() || ! $lv->approved_at) && $canp)
          <button class='btn-approve btn btn-success' mode='2'>
            Approve
          </button>
        @else
          {!! $lv->approved_at ? date('d-M/H:i', strtotime($lv->approved_at) ) : '-' !!}
        @endif
        </td>
        <td>
        @if($lv->state == 9)
          <div class='btn-veto badge badge-danger' mode='3'>
            Veto
          </div>
          @endif
        </td>
      </tr>
    @endforeach
    @else
      <tr><td class="h3 text-center">No Record</td></tr>
    @endif
    </thead>
  </tbody>
</table>
<!-- </div> -->


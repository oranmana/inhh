@php( $lv = \App\Models\LeaveRq::find($lvid) )
<form id="leavecard" method="POST">
  {{ csrf_field() }}
  <input type="hidden" name="lvid" value="{{ $lv->id }}">
  <input type="hidden" name="empid" value="{{ $lv->empid }}">
  <div class="row">
    <label>Name / ชื่อพนักงาน</label>
    <input type="text" class="form-control" value="{!! $lv->emp->fullname . ' / ' . $lv->emp->thname  . ' ['. $lv->emp->pos->code . ']' !!} ">
  </div>
  <div class="row">
  @if( $lv->tilldate > $lv->fromdate)
    <label>From / ตั้งแต่วันที่</label>
  @else
    <label>For Date / วันที่</label>
  @endif
    <input type="text" class="form-control" value="{{ date('d-M-Y (D)', $lv->fromdate) }}">
  </div>
  @if( $lv->tilldate > $lv->fromdate)
  <div class="row">
    <label>To / ถึงวันที่</label>
    <input type="text" class="form-control" value="{{ date('d-M-Y (D)', $lv->tilldate) }}">
  </div>
  @endif
  <div class="row">
    <label>Type / ประเภทการลา</label>
    <select class="form-control" name="leavetype" id="leavetype" readonly>
      @php( $leaves = \App\Models\Leave::where('off',0)->orderBy('par')->orderBy('num')->get() )
      @foreach($leaves as $lvtype) 
        <option value="{{ $lvtype->id }}" {!! $lv->lvid == $lvtype->id ? "SELECTED" : '' !!}  reason="{{ $lvtype->reasoned }}">({{ $lvtype->code }}) {{ $lvtype->name }} / {{ $lvtype->tname}} </option>
      @endforeach
    </select>
  </div>
  <div class="row">
    <label>Reason / เหตุผล</label>
    <textarea class="form-control" name="reason" id="reason" size=3>{{ $lv->rem}}</textarea>
  </div>
  <hr>
  <div class="row">
    <div class="col-3"><strong>Requested</strong></div>
    <div class="col-3"><strong>Verified</strong></div>
    <div class="col-3"><strong>Approved</strong></div>
    <div class="col-3" title="HR ไม่อนุมัติการจ่าย"><strong>HR Veto</strong></div>
  </div>
  <div class="row">
    <div class="col-3">{{ edate($lv->requested_at,22) }}</div>
    <div class="col-3">{{ $lv->verified_at ? edate($lv->verified_at,22) : '-' }}</div>
    <div class="col-3">{{ $lv->approved_at ? edate($lv->approved_at,22) : '-' }}</div>
    <div class="col-3">{{ $lv->state == 9 ? edate($lv->UPDATED_AT,22) : '-' }}</div>
  </div>
  <div class="row">
    <div class="col-3">{{ 'by '. ucwords($lv->requestor->username) }}</div>
    <div class="col-3">{{ $lv->verified_by ? 'by '. ucwords($lv->verifier->username) : '-' }}</div>
    <div class="col-3">{{ $lv->approved_by ? 'by '. ucwords($lv->approver->username) : '-' }}</div>
    <div class="col-3">{!! $lv->state == 9 ? 'by HR TM' : ( age($lv->fdate,0,2) ? '-' : 
      "<button class='btn-veto btn btn-danger' mode='3'>Veto</button>") !!}</div>
  </div>
</form>
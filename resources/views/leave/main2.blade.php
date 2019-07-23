@extends('layouts.app') 

@section('content')
@php( $user = auth()->user() )
@if( empty($yr) )
  @php($yr = date('Y'))
@endif
<div class="container" id="leaverequests">
  <div class="row form-inline">
    <label for="#rqyr" id="leavetitle" value="1" class="h3 mr-3">Leave Request</label>
    <select class="form-control col-1 mr-3" id="rqyr">
    @for($y=date('Y')+(date('m') > 9 ? 1 : 0); $y > date('Y')-3; $y--)
      <option value="{{ $y }}"{!! $y==$yr ? ' selected' : '' !!}>{{ $y }}</option>
    @endfor
    </select>

    @can('isHR')
        <label for="#rqemp" class="mr-3">Under</label>
        <select id="rqemp" class="form-control col-2">
        @php( $emps = \App\Models\Emp::isActive()->OfNotWorker()->orderBy('name')->get() )
          <option value="0">-select-</option>
        @foreach($emps as $emp) 
          @if(! empty($emp->LowerEmps) )
          <option value="{{ $emp->id }}"{!! $emp->id == auth()->user()->empid ? " selected" : '' !!}>{{ $emp->name }}</option>
          @endif
        @endforeach
        </select>
      @else
        <input type="hidden" id="rqemp" value="{{ auth()->user()->empid }}">
      @endcan

    <div class="mr-3">
      <ul id="leavemodes" class="nav nav-tabs">
        @php( $pending = \App\Models\LeaveRq::OfYear($yr)->UnderUser($user->id)->Pending() )
        <li class="nav-item"><a class="nav-link leavemode active" count="{{ $pending->count() }}" value="1">Requested{!! $pending->count() ? ' ('. $pending->count() . ')' : '' !!}</a></li>

        @php( $approved = \App\Models\LeaveRq::OfYear($yr)->ApprovedBy($user->id) )
        <li class="nav-item"><a class="nav-link leavemode" count="{{ $approved->count() }}" value="2">Approved{!! $approved->count() ? ' ('. $approved->count() . ')' : '' !!}</a></li>

        @php( $rejected = \App\Models\LeaveRq::OfYear($yr)->UnderUser($user->id)->whereNull('approved_at') )
        <li class="nav-item"><a class="nav-link leavemode" count="{{ $rejected->count() }}" value="3">Rejected{!! $rejected->count() ? ' ('. $rejected->count() . ')' : '' !!}</a></li>

      </ul>
      </div>
  </div>
  <div id="resleave">
  </div>
</div>
@endsection

@section('script')
<script>
// Initialization
$(document).ready(function() {
  // Mode Select
  $('.leavemode').on('click', function() {
    $('.leavemode').removeClass('active');
    $(this).addClass('active');
    getleaverequest();
  });
  $('#rqyr, #rqemp').on('change', function() {
    if ($('#rqemp').val()) {
      getleaverequest();
    }
  });
  $('#leavetitle').on('click', function() {
    switchrequest(1);
  });

  getleaverequest();
}); // end of doc.ready

function getleaverequest() {
  var yr = $('#rqyr').val();
  var mode = $('.leavemode.active').attr('value');
  var empid = $('#rqemp').val();
//  console.log(yr, mode, empid)
  if ( empid>0 ) {
    $.ajax({
      type  : "GET",
      url   : "{{ url('/leave') }}" + "/" + yr + "/" + mode + "/" + empid
    }).done(function(data) {
      $('#resleave').empty().html(data);
      switchrequest(0);
//      arrangehead();
    });
  }
}

function switchrequest(sw) {
  var leavedf = $('lable#leavetitle').attr('value');
  var leavenow = leavedf;
  if (sw==1) leavenow = (leavedf == 1 ? 0 : 1);
  $(this).attr('value', leavenow);
  $('label#leavetitle').text(leavenow ? 'Leave Request' : 'Outdoor Request');
  $('.outdoorrequest, .leaverequest').closest('tr').hide();
  if(leavenow) {
    $('.leaverequest').closest('tr').show();
  } else {
    $('.outdoorrequest').closest('tr').show();
  }
}

</script>
@endsection


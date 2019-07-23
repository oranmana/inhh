@extends('layouts.app') 

@section('content')
@php( $uempid = auth()->user()->empid )

@if( empty($yr) )
  @php($yr = date('Y'))
@endif

@if(empty($empid))
  @php( $empid = $uempid )
@endif

@php ($rqemp = \App\Models\Emp::find($uempid) )

<div class="container" id="leaverequests">
  <div class="row form-inline">
    <label for="#rqyr" id="leavetitle" value="1" class="h3 mr-3">Leave Request</label>
    <select class="form-control col-1 mr-3" id="rqyr">
    @for($y=date('Y')+(date('m') > 9 ? 1 : 0); $y > date('Y')-3; $y--)
      <option value="{{ $y }}"{!! $y==$yr ? ' selected' : '' !!}>{{ $y }}</option>
    @endfor
    </select>

    @can('isHR')
      @php( $emps = \App\Models\Emp::isActive()->OfNotWorker()->orderBy('name')->get() )
    @else
      @php( $emps = $rqemp->LowerEmps->isActive()->OfNotWorker()->orderBy('name')->get() )
    @endif

    @if($emps->count() > 1)
      <label for="#rqemp" class="mr-3">Under</label>
      <select id="rqemp" class="form-control col-2">
        <option value="{{ $rqemp->id ?? 0 }}">{{ $rqemp->name ?? '-Select-' }}</option>
      @foreach($emps as $emp)
        @if(! empty($emp->LowerEmps) )
        <option value="{{ $emp->id }}"{!! $emp->id == $empid ? " selected" : '' !!}>{{ $emp->name }}</option>
        @endif
      @endforeach
      </select>
    @else
      <input type="hidden" id="rqemp" value="{{ $uempid }}">
    @endcan

    <div class="ml-3">
      <input type="radio" name="leavemode" value=0 checked> Requested
      <input type="radio" name="leavemode" value=2> Approved
      <input type="radio" name="leavemode" value=1> Rejected
    </div>
  </div>
  <div id="resleave">
  {{--  @include('leave.list', compact('uempid') ) --}}
  </div>
</div>

@include('partials.modal')
@endsection

@section('script')
<script>
// Initialization
$(document).ready(function() {
  // Mode Select
  $("#leavetitle").on('click', function() {
    switchrequest();
  });
  $('#rqyr, #rqemp').on('change', function() {
    if ($('#rqemp').val()) {
      $('#resleave').empty().html("<center class='text-primary mt-5'>Loading...</center>");
      getleaverequest();
    }
  });
  $('[name=leavemode]').on('change', function() {
    viewrequest();
  });
  $('.btn-verify, .btn-approve, .btn-veto').on('click', function() {
    var mode = $(this).attr('mode');
    leaveapprove($(this).closest('tr').attr('data-id'), mode);
  });
  getleaverequest();
}); // end of doc.ready

function openleave(lvid) {
  $.ajax({
    type : "GET",
    url : "{{ url('leave/card') }}" + "/" + lvid
  }).done(function(data) {
    $('#ModalLong').removeClass('modal-lg');
    $('#ModalLongTitle').empty().html('<h3>Leave Request #' + lvid + '</h3>');
    $('#ModalBody').addClass('p-5');
    $('#ModalBody').empty().html(data);
    $('.btn-verify, .btn-approve, .btn-veto').on('click', function() {
      var mode = $(this).attr('mode');
      leaveapprove($(this).closest('tr').attr('data-id'), mode);
    });

  });
}

function leaveapprove(rqid, mode) {
  event.stopPropagation();
  var csfr_token = $('meta[name="csrf-token"]').attr('content');
  $.ajax({
    url : "{{ url('leave/approve') }}",
    type : "POST",
    data : {_token: csfr_token, rqid: rqid, mode: mode},
    dataType : "HTML",
    success : function(data) {
      alert(data);
      $('#resleave').empty().html("<center class='mt-10 text-grey'>...Loading...</center>");
    }
  }).done(function(data) {
    getleaverequest();
  });
}

function getleaverequest() {
  var yr = $('#rqyr').val();
  var mode = $("input[name='leavemode']").val();
  var empid = $('#rqemp').val();
  if ( empid>0 ) {
    $.ajax({
      type  : "GET",
      url   : "{{ url('/leaves') }}" + "/" + yr + "/" + empid
    }).done(function(data) {
      $('#resleave').empty().html(data);
      $('.lvcard').on('click', function() {
        openleave($(this).text());
      });
      viewrequest();
    });
  }
}

function switchrequest() {
  var leavedf = $('#leavetitle').attr('value');
  if (leavedf == 3) {
    leavedf = 1;
  } else {
    leavedf++;
  }
  $('#leavetitle').attr('value', leavedf);

  var leavetitle = ['', 'Leave', 'Training', 'Outdoor'];
  $('#leavetitle').text( leavetitle[leavedf] + ' Request' );

  viewrequest();
}
function viewrequest() {
  $('.btn-verify, .btn-approve, .btn-veto').on('click', function() {
    var mode = $(this).attr('mode');
    leaveapprove($(this).closest('tr').attr('data-id'), mode);
  });

  var rq = $('#leavetitle').attr('value');
  var ap = $("input[name='leavemode']:checked").val();
  $("tr[class^='rq']").hide();
  $('tr.rq'+rq+ '.ap'+ap).show();
}

</script>
@endsection


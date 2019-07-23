@extends('layouts.app') 

@section('content')
@php( $user = \Auth::user() )
@if( empty($yr) )
  @php($yr = date('Y'))
@endif
@if( empty($wk) )
  @php( $wk = date('W') )
@endif
<div class="container" id="leaverequests">
  <div class="row form-inline">
    <label for="#rqyr" id="leavetitle" value="1" class="h3 mr-3">OT Order</label>
    <select class="form-control col-1 mr-3" id="rqyr">
    @for($y=date('Y'); $y > date('Y')-3; $y--)
      <option value="{{ $y }}"{!! $y==$yr ? ' selected' : '' !!}>{{ $y }}</option>
    @endfor
    </select>
    <select class="form-control col-1 mr-3" id="rqwk">
    @php($lastdate = ($yr == date('Y') 
      ? date('Y-m-d', strtotime('+ 3 week'))
      : $yr . '-12-31'
      )
    );
    @php($ws = ($yr==date('Y') ? date('W', strtotime($lastdate)) : 52) )
    @php($xm = \App\Models\Calendar::selectRaw('week(cldate,1) as wk')->where('cldate',$lastdate)->pluck('wk'))
    @php($ws = \App\Models\Calendar::whereYear('cldate',2018)->selectRaw('week(cldate,1) as wk')->groupBy('wk')->orderBy('wk','desc')->pluck('wk'))
    @foreach($ws as $w)
      <option value="{{ $w }}"{!! $w==$wk ? ' selected' : '' !!}>{{ zero($w,2) }}</option>
    @endforeach
    </select>
    @can('isHR')
    <select id="rqemp" class="form-control col-2">
      @php( $emps = \App\Models\Emp::isActive()->OfNotWorker()->orderBy('name')->get() )
        <option value="0">-Under-</option>
      @foreach($emps as $emp) 
        @if(! empty($emp->LowerEmps) )
        <option value="{{ $emp->id }}"{!! $emp->id == auth()->user()->empid ? " selected" : '' !!}>{{ $emp->name }}</option>
        @endif
      @endforeach
    </select>
    @else
    <input type="hidden" id="rqemp" value="{{ auth()->user()->empid }}">
    @endcan
  </div>
  <div id="resotorder">
  </div>
</div>

@include('partials.modal')

@endsection

@section('script')
<script>
// Initialization
$(document).ready(function() {
  // Mode Select
  $('#rqyr').on('change', function() {
    var yr = $('#rqyr').val();
    window.location = "{{ url('/otorder') }}" + "/" + yr;
  });
  $('#rqwk, #rqemp').on('change', function() {
      getemps();
  });

  getemps();
}); // end of doc.ready

function getemps() {
  var yr = $('#rqyr').val();
  var wk = $('#rqwk').val();
  var underemp = $('#rqemp').val();
  $.ajax({
    type  : "GET",
    url   : "{{ url('/otorder/emps') }}" + "/" + underemp + "/" + yr + "/" + wk 
  }).done(function(data) {
    $('#resotorder').empty().html(data);
    $('.tbl-content').css('height', (window.innerHeight - $('.tbl-content').offset().top) + 'px' )
    .css('overflow-y','scroll');

    $('.attn').on('click', function() {
      var attnid = $(this).attr('data-id');
      setattn(attnid);
    });

  });
}

function setattn(attnid) {
  $('#ModalLong').removeClass('modal-lg');
  $.ajax({
    type  : "GET",
    url   : "{{ url('/otorder/attn') }}" + "/" + attnid
  }).done(function(data) {
    $('#ModalLongTitle').empty().html('OT Oder #'+attnid).addClass('h3');
    $('#ModalBody').empty().html(data);
    $('#ot1,#ot2,#ot3,#shift').on('change', function() {
      otchange();
    });
    $('#btnsave').on('click', function() {
      $.ajax({
        method : "POST",
        data : $('form#otorder').serialize(), 
        url : "{{ url('otorder/save') }}",
        success : function(data) {
          console.log(data);
          $('.attn[data-id=' + data[0] + ']').text( data[1] );
        }
      });
    });
    otchange();
  });
}

function otchange() {
  var dt = $('#date').val();
  var sh = $('#shift').val();
  var ot1 = $('#ot1').val();
  var ot2 = $('#ot2').val();
  var ot3 = $('#ot3').val();
  $.ajax({
    type  : "GET",
    url   : "{{ url('/otcode') }}" + "/" + dt + "/" + sh + "/" + ot1 + "/" + ot2 + "/" + ot3
  }).done(function(data) {
    $('#wdate').val(data);
  });
}
</script>
@endsection


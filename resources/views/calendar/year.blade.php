@extends('layouts.app')

@section('content')
@php ( $user = auth()->user() )
@php ( $yearfirstdate = $years->first() )
@php ($yr = date('Y', $yearfirstdate->TimeStr) )
<div class="container">

<div class="page" id="page1"
  {!! ($yr > 2011 ? " prev='" . ($yr-1) . "'" : '') !!}
  {!! ($yr < date('Y') + 1 ? " next='" . ($yr+1) . "'" : '') !!}
>
  <div class="calendartitle h2 text-center">Calendar {{ $yr }}</div>
  <table class="table table-sm">
    @for ($i=1; $i <= 12; $i++)
      @if($i%3 == 1)
        <tr>
      @endif
      <td>
        @php ( $month = App\Models\Calendar::month($yr, $i)->orderBy('cldate')->get() )
        @include('calendar.month', ['month', $month] )
      </td>
      @if($i%3 == 0)
        </tr>
      @endif
    @endfor
  </table>
</div>

<div class="page" id="page2">
  <div class="h2 text-center calendartitle">{{ $year }} Public Holidays </div>
  <table class="table table-striped table-hover table-sm">
  <tr class="bg-primary text-center">
    <th width="5%">No.</th>
    <th width="20%">Date</th>
    <th>Description</th>
  </tr>
  @php( $pbholidays = \App\Models\Calendar::year($year)->whereHoliday(2)->orderBy('cldate')->get() )
  @php( $ln=1)
  @foreach($pbholidays as $pb)
  <tr>
    @php( $over = strtotime($pb->cldate) > strtotime('now') )
    <td class="text-right">{{ $ln++ }}.</td>
    <td {!! $pb->daynum==6 ? " class='text-primary'" : '' !!}>{{ date('(D) d-M-Y', $pb->TimeStr) }}</td>
    <td><a 
    @if($over) 
     class="pb" data-id="{{ $pb->id }}" data-toggle="modal" data-target="#ModalLong"
    @endif
    >{{ $pb->rem }}</td>
  </tr>
  @endforeach
  </table>
  
  @can('isHR')
    {{-- @if ( ($user->isMaster || $user->isHRTM) && $year > date('Y') ) --}}
    @if ( $user->isMaster || $user->isTM)
      <button class="col btn btn-danger" id="btnaddholiday"
        data-toggle="modal" data-target="#ModalLong"
      >Add Public Holiday</button>
    @endif
  @endcan
</div>

</div>
@include('partials.modal')
@endsection

@section('script')
<script>
  $(document).ready(function() {
    $(".calendartitle").on('click', function() {
      $(".page").toggle();
    });

    $('#page1').on('keyup', function(event) {
      var key = event.keyCode;
      var next = $('#page1').attr('next');
      var prev = $('#page1').attr('prev');
      // up/down arraow
      if (key==40 || key==38) {
        event.preventDefault();
        $('.page').toggle();
        return false;
      }
      // right arraow
      if ( key==39 && next ) {
        location.href = "{{ url('calendars') }}" + "/" + next;
      }
      // right left
      if ( key==37 && prev ) {
        location.href = "{{ url('calendars') }}" + "/" + prev;
      }
    });

    $('.pb').on('click', function() {
      caledit($(this).attr('data-id'));
    });
    $('#btnaddholiday').on('click', function() {
      caledit(0);
    });
    $('#page2').hide();

    function caledit(calid) {
      var yr = {{ $yr }};
      if(! calid) calid = 0;
      $('#ModalLongTitle').text(yr + ' Public Holiday').addClass('h3');
      $.ajax({
        method : "GET",
        url : "{{ url('calendar/addholiday') . '/' . $yr }}" + '/' + calid
      }).done(function(data, calid) {
        $('#ModalBody').html(data);
        var calid = $('input[name=calid]').val();
        $('.modal-dialog').removeClass('modal-lg');
        $('#caldate').hide();
        if (calid > 0) {
          $('#btnupdate').html('Remove').removeClass('hidden').show();
        } else {
          $('#btnupdate').addClass('hidden');
        }

        var remove = "<button id='btnreset' type='button' data-dismiss='modal' class='btn btn-warning'>Remove Holiday</button>";
        $("#caldate").on('focusout', function(event) {
          event.stopPropagation();
          $('#caldatetxt').val( edate( $(this).val(), 3) );
          $('.datefield').toggle();
        });
        $("#caldatetxt").on('focusin', function(event) {
          event.stopPropagation();
          $('.datefield').toggle();
        });
        $('#btnsave').on('click', function() {
          $('form#addholiday').submit();
        });
        $('#btnupdate').on('click', function() {
          $('#mode').val(9);
          $('form#addholiday').submit();
        });
      });

    }
  });

  function edate( targetdate, mode ) {
    var tdate = new Date(targetdate);
    $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $wkdays = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

    var d = $text = tdate.getDate();
    var m = $months[tdate.getMonth()];
    var Y = tdate.getFullYear();
    var w = $wkdays[tdate.getDay()]; // Day of Week
    // $text = tdate.getDate() + '-' + $months[tdate.getMonth()] + '-' + tdate.getFullYear();
    $text = d + '-' + m + '-' + Y;
    if (mode==3) $text = $text + ' ('+w+')';

    return $text;
  }
</script>  
@endsection
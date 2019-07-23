@extends('layouts.app')

@section('content')
<div class="container">
  <a class="h3 text-primary" href="{{ URL::previous() }}">Training Registration</a>
  <form>
  <div class="row col">
    <input type="hidden" name="id" value="{{ $train->id }}">
    <div class="col-2">
      <label>Code : </label>
      <input class="form-control" type="text" name="code" value="{{ $train->code }}">
      <label>From : </label>
      <input class="form-control dateinput" type="date" name="ondate" value="{{ edate($train->ondate,10) }}">
      <input class="form-control dateview" type="text" value="{{ edate($train->ondate) }}">
      <label>Until : </label>
      <input class="form-control dateinput" type="date" name="todate" value="{{ edate($train->todate,10) }}">
      <input class="form-control dateview" type="text" value="{{ edate($train->todate) }}">
    </div>
    <div class="col-8">
        <label >Course : </label>
        <textarea class="form-control" name="coursename"  rows=1>{{ $train->coursename }}</textarea class="form-control">
        <label >Location : </label>
        <textarea class="form-control" name="place" rows=1>{{ $train->place }}</textarea class="form-control">
      @if($train->organizer)
        <label for="organize" class="justify-left">Organizer : </label>
        <input class="form-control" name="organize" value="{{ $train->organizer->name }}">
      @endif
        <label for="remark">Remark : </label>
        <textarea class="form-control" name="remark">{{ $train->remark }}</textarea class="form-control">
    </div>
    <div class="col-2">
      <label for="fees">Fees : </label>
      <input class="form-control text-right" type="text" name="fees" value="{{ $train->amt_fees }}">
      <label for="expenses">Other Expenses : </label>
      <input class="form-control text-right" type="text" name="expenses" value="{{ $train->amt_expenses }}">
      <label for="cost">TOTAL COST </label>
      <input class="form-control text-right" type="text" name="cost" value="{{ $train->Amount }}">
    </div>
  </div>
  </form>
  <div class="col m-5">
    <div class="float-left h3 text-primary m-0">Attendees</div>
      <a id="btnaddattendee" 
        class="float-right badge badge-info mb-0 border-0" 
        data-toggle="modal" data-target="#ModalLong"
      >&plus;</a>
      <div class="btn-group dropleft">
        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropleft
        </button>
        <div id="newattn" class="dropdown-menu">
        </div>
      </div>      
  </div>
  <table class="table table-sm table-hover">
    <thead>
      <tr class="text-center align-middle bg-info" class="bg-info">
        <th>No.</th>
        <th>Employee</th>
        <th>Date</th>
        <th>Hour</th>
        <th>#Leave</th>
        <th class="small">Fees</th>      
        <th class="small">Expenses</th>
        <th class="small">Cost</th>
      </tr>
    </thead>
    <tbody>
      @php($attendees = $train->trainings)
      @if( $attendees->count() )
      @php($ln=1)
      @foreach($attendees as $trs)
        @php ($emp = $trs->Employee)
        <tr data-id="{!! $trs->id !!}" align="right">
          <td>{!! $ln++ !!}.</td>
          <td align="left">{!! $emp->name . ' / ' . $emp->curjob->name !!}</td>
          <td align="center">{!! edate($trs->traindate) !!}</td>
          <td>{!! fnum($trs->trainhours,0,0,2) !!}</td>
          <td align="center">{!! ($trs->leaverq_id ? '#'.$trs->leaverq_id : '') !!}</td>
          <td>{!! fnum($trs->fees,0,0,2) !!}</td>
          <td>{!! fnum($trs->expenses,0,0,2) !!}</td>
          <td>{!! fnum($trs->Amount,0,0,2) !!}</td>
        </tr>
      @endforeach
      @endif
    </tbody>
  </table>
</div>

@include('partials.modal')
@endsection

@section('script')
<script>
  $(document).ready(function() {
    $('.dateview').on('focusin', function() {
      $(this).prev('.dateinput').show();
      $(this).hide();
    });
    $('.dateinput').on('focusout', function() {
      $(this).next('.dateview').show();
      $(this).hide();
    });
    
    $('#btnaddattendee').on('click', function() {
      addattendee($("input[name='id']").val());
    });

    $('.dateinput').hide();
  });

  function addattendee(trainid, attnid=0) {
    $.ajax({
      method : "GET",
      url : "{{ url('train/attendee/add') }}" + "/" + trainid + "/" + attnid
    }).done(function(res) {
      $('#ModalBody').empty().html(res);
      $('#empselect').on('click', function() {
        var n = $('#empselect option:selected').length;
        $('#selectcount').html( n ? n + ' selected' : '');
        $('#btnsave').on('click', function() {
          
        });
      });
    });
  }
</script>
@endsection
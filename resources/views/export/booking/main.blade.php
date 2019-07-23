@extends('layouts.app')

@section('content')
<div class="container">
  <div id="headbar">
    <h2 class="text-info"><a href="{{ url('sale') }}">Export Booking</a></h2> 
    <div class="float-left form-inline">
      <div class="h3">{{ date('F, Y', strtotime($yymm.'01')) }}</div>
    </div> 
    <div class="float-right">
      <div class="form-inline" id='page' value="1">
        <a id="newbook" class="btn btn-sm">New</a> 
      </div>
    </div>
  </div>
  
  <div class="tbl-header">
    <table class="table table-sm">
      <thead>
        <tr class="bg-info text-center header">
          <th>Date</th>
          <th>Number</th>
          <th>LCL</th>
          <th>20'</th>
          <th>20'H</th>
          <th>40'</th>
          <th>40'H</th>
          <th>Agent</th>
          <th>Vessel</th>
          <th>CY</th>
          <th>RTN</th>
          <th>ETD</th>
          <th>Inv</th>
          <th>State</th>
        </tr>
      </thead>
    <!-- </table>
  </div>

  <div class="tbl-content">
    <table class="table table-sm table-hover table-striped"> -->
      <tbody>
        @include('export.booking.list', array(['yymm'=>$yymm, 'invid'=>0 ]))
      </tbody>
    </table>
  </div>
</div>
@include('partials.modal')
@endsection

@section('script') 
<script>
  $(document).ready(function() {

    $('.bookrow').on('click', function() {
      var bookid = $(this).attr('data-id');
      location.href = "{{ url('booking/view') }}" + '/' + bookid;
    });
    $('#newbook').on('click', function() {
      location.href = "{{ url('booking/view/0') }}";
    });
  });
</script>
@endsection

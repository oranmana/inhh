@extends('layouts.app')

@section('content')
<!-- requirement from route $domid, $year, $month -->
@php( $saleyrs = \App\Models\mkinvoice::selectRaw('year(bldate) as yr')->whereRaw('year(bldate) > 0')->groupBy('yr')->orderBy('yr','desc')->get() )    
@php( $salemonths = \App\Models\mkinvoice::selectRaw("month(bldate) as mth, date_format(bldate,'%M') as name")->whereYear('bldate', $year)->groupBy('mth')->orderBy('mth','desc')->get() )
<div class="container" scr="export.list">
  <div id="headbar">
    <h2 class="text-info"><a href="{{ url('/') }}/home">Sales Log</a></h2> 
    <div class="float-left form-inline">
      <label for="rqyr" class="form-control btn-primary">Year </label> 
      <select id="rqyr" name="rqyr" class="form-control rqmenu">
        @foreach($saleyrs as $yr)
          <option value="{{ $yr->yr }}"{!! ($yr->yr == $year ? " SELECTED" : '') !!}>{{ $yr->yr }}</option>
        @endforeach
      </select>

      <label for="rqmth" class="form-control btn-primary">Month </label> 
      <select id="rqmth" name="rqmth" class="form-control rqmenu">
        @foreach($salemonths as $mth)
          <option value="{{ $mth->mth }}"{!! ($mth->mth == $month ? " SELECTED" : '') !!}>{{ $mth->name }}</option>
        @endforeach
      </select>

      <label for="rqgrp" class="form-control btn-primary">Group </label> 
      <select id="rqgrp" name="rqgrp" class="form-control rqmenu">
        <option value="1"{!! $domid==1 ? " SELECTED" : '' !!}>Export</option>
        <option value="2"{!! $domid==2 ? " SELECTED" : '' !!}>Indirect Export</option>
        <option value="3"{!! $domid==3 ? " SELECTED" : '' !!}>Domestic</option>
      </select>

    </div> 
    <div class="float-right">
      <div class="form-inline" id='page' value="1">
        <a id="pagemain" class="btn btn-sm">Main</a> 
        <a id="pageschedule" class="btn btn-sm">Schedule</a> 
        <a id="pagepoints" href="{{ url('points') }}" class="btn btn-sm">Point ID</a> 
        <a id="newinvoice" class="btn btn-sm">New</a> 
      </div>
    </div>
  </div>
  
  <div class="tbl-header">
    <table class="table table-sm">
      <thead>
        <tr class="bg-info text-center header">
        @if($page==1)
          <th>InvNum</th>
          <th>S/O</th>
          <th>Order</th>
          <th>Customer</th>
          <th>Port</th>
          <th>Price</th>
          <th>20'</th>
          <th>40'</th>
          <th>USD</th>
          <th>Amount</th>
          <th>B/L Date</th>
          <th>PIC</th>
          <th>State</th>
        @endif
        @if($page==2)
          <th>InvNum</th>
          <th>Customer</th>
          <th>20'</th>
          <th>40'</th>
          <th>Booking</th>
          <th>Feeder</th>
          <th>Closed</th>
          <th>ETD</th>
          <th>ETA</th>
          <th>Port</th>
          <th>CY</th>
          <th>Return</th>
        @endif
        </tr>
      </thead>
    </table>
  </div>

  <div class="tbl-content">
    <table class="table table-sm table-hover table-striped">
      <tbody>
        @php( $yymm = date('Ym',strtotime($year.'-'.$month.'-01')) )
        @if($page==1)
          @include('export.loglist', array(['yymm'=>$yymm, 'domid'=>$domid ]))
        @endif
        @if($page==2)
          @include('export.logschedule', array(['yymm'=>$yymm, 'domid'=>$domid ]))
        @endif
      </tbody>
    </table>
  </div>
</div>
@include('partials.modal')
@endsection

@section('script') 
<script>
  $(document).ready(function() {

    $('#pagemain').on('click', function() {
      $('#page').val(1);
      $('#rqyr').trigger('change');
    });
    $('#pageschedule').on('click', function() {
      $('#page').val(2);
      $('#rqyr').trigger('change');
    });

    $('#rqyr, #rqmth, #rqgrp').on('change', function() {
      let url = "{{ url('sales') }}" + '/' + $('#rqgrp').val() + '/' + $('#rqyr').val() + '/' + $('#rqmth').val() + "/" + $('#page').val();
      window.location = url;
    });

    $('.datarow').on('click',function() {
      url = "{{ url('sale') }}" + '/' + $(this).attr('data-id') ;
      window.location = url;
    });
    
    $('#newinvoice').on('click', function() {
      var url = "{{ url('/export/new') }}";
      $.ajax({
        type : "GET",
        url : url        
      }).done(function(data) {
        $('#ModalLongTitle').html('New Invoice');
        $('div').removeClass('modal-lg');
        $('#ModalBody').empty().html(data);
        $('#ModalLong').modal('show');
        $('#btnupdate').hide();
      });
    });

    arrangehead();

  });
</script>
@endsection

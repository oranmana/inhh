@extends('layouts.app')

@section('content')
<div class="container" scr="export.pages.points">
  <div id="headbar">
    <h2 class="text-info"><a href="{{ url('/') }}/home">Point ID</a></h2> 
    <div class="float-left form-inline">
      <div class="form-group">
        <label for="customerid" class="form-control btn-primary">Customer </label> 
        <select name="customerid" id="customerid" class="form-control headmenu" required>
          <option value=0>-Any Customer-</option>
          @php ( $customers = App\Models\mkcustomer::ASR()->where('nm','>','')->orderby('name') )
          @foreach($customers->get() as $cs)
          <option value="{{ $cs->id }}"{!! ($customerid == $cs->id ? " SELECTED" : '') !!}>{{ strtoupper($cs->name) }} [{{$cs->nm}}]</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="countryid" class="form-control btn-primary">Country </label> 
        <select name="countryid" id="countryid" class="form-control headmenu" required>
          <option value=0>-Any Country-</option>
          @php($countries = App\Models\Country::Countries()->where('name','>','')->orderby('name'))
          @foreach($countries->get() as $cty)
          <option value="{{ $cty->id }}"{!! ($countryid == $cty->id ? " SELECTED" : '') !!}>{{ $cty->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="priceterm" class="form-control btn-primary">Price Term </label> 
        <select id="priceterm" class="form-control headmenu" value="0">
        <option value=0>-Any Price Term-</option>
          @php($pts = App\Models\priceterm::on()->get())
          @foreach($pts as $pt)
            <option value="{{ $pt->id }}{!! ($priceid == $pt->id ? " SELECTED" : '') !!}">{{ $pt->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="payterm" class="form-control btn-primary">Payment </label> 
        <select id="payterm" class="form-control headmenu" value="0">
          <option value=0>-Any Payment Term-</option>
          @php($pys = App\Models\payterm::on()->get())
          @foreach($pys as $py)
            <option value="{{ $py->id }}"{!! ($payid == $py->id ? " SELECTED" : '') !!}>{{ $py->name }}</option>
          @endforeach
        </select>
      </div>
    </div> 
    <div class="float-right">
      <div class="form-inline" id='page' value="1">
        <a href="{{ url('sales') }}" class="btn btn-sm">Sales Log</a> 
        <a id="newpoint" class="btn btn-sm">New Point ID</a> 
      </div>
    </div>
  </div>
  
  <div class="tbl-header">
    <table class="table table-sm">
      <thead>
        <tr class="bg-info text-center header">
          <th>Code</th>
          <th>Customer</th>
          <th>Country</th>
          <th>Port</th>
          <th>Price Term</th>
          <th>Payment Term</th>
        </tr>
      </thead>
    </table>
  </div>

  <div class="tbl-content">
    <table class="table table-sm table-hover table-striped">
      <tbody id="pointtable">
        @include('export.points.pointlist',array('customerid'=>$customerid, 'countryid'=>$countryid, 'priceid'=>$priceid, 'payid'=>$payid) )
      </tbody>
    </table>
  </div>
</div>
@include('partials.modal')
@endsection

@section('script') 
<script>
  $(document).ready(function() {

    $('.headmenu').on('change', function() {
      var customerid = $('#customerid').val();
      var countryid = $('#countryid').val();
      var priceid = $('#priceterm').val();
      var payid = $('#payterm').val();

      var url = "{{ url('pointlist') }}" + '/' + customerid + '/' + countryid + '/' + priceid + '/' + payid;
      $.ajax({
        type : "GET",
        url : url        
      }).done(function(data) {
        $('#pointtable').empty().html(data);
        arrangehead();
        newallow();
      });

    });

    function newallow() {
      var customerid = $('#customerid').val();
      var countryid = $('#countryid').val();
      if (customerid>0 && countryid>0) {
        $('#newpoint').show();
      } else {
        $('#newpoint').hide();
      }
    }

    $('#newpoint').on('click', function() {
      var customerid = $('#customerid').val();
      var countryid = $('#countryid').val();
      var url = "{{ url('point/create') }}" + '/' + customerid + '/' + countryid;
      $.ajax({
        method : "GET",
        url : url
      }).done(function (data) {
        $('#ModalLongTitle').html('New Point ID');
        $('#ModalBody').empty().html(data);
        $('div').removeClass('modal-lg');
        $('#ModalLong').modal('show');
        $('#btnsave, #btnupdate').hide();
        $('#btnsave').show();
      })
    });

    arrangehead();
    newallow();

  });
</script>
@endsection

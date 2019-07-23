@extends('layouts.app')

@section('content')

@php($cr = $inv->credit)
@php($custom = $inv->customs )
@php($pic = $inv->pic )
@php($book = $inv->booking )
@php($credit = $inv->credit )
@php($payterm = $inv->payterm )
@php($currency = $inv->currency )
@php($priceterm = $inv->priceterm )

@php($point = $inv->point)
@php($customer = $point->dir)

<div class="container-fluid" src="export.card" invid="{{ $inv->id }}" id="invoice">
  <div class="row ">
    <div id="menu" class="col-9">
      <div class="h2"><a href="{{ url('sales') }}">Sale Invoice</a></div>
      <ul class="nav nav-tabs" id="exporttab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab" aria-controls="home" aria-selected="true">Shipment Data</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="items-tab" data-toggle="tab" href="#items" role="tab" aria-controls="items" aria-selected="false">Items</a>
        </li>
        @if($inv->creditid)
        <li class="nav-item">
          <a class="nav-link" id="credit-tab" data-toggle="tab" href="#credit" role="tab" aria-controls="credit" aria-selected="false">Credit</a>
        </li>
        @endif
        @if($inv->bookid)
        <li class="nav-item">
          <a class="nav-link" id="book-tab" data-toggle="tab" href="#book" role="tab" aria-controls="book" aria-selected="false">Booking</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="containers-tab" data-toggle="tab" href="#containers" role="tab" aria-controls="containers" aria-selected="false">Containers</a>
        </li>
        @endif
        @if($inv->bookid && $inv->creditid)
        <li class="nav-item">
          <a class="nav-link" id="document-tab" data-toggle="tab" href="#document" role="tab" aria-controls="document" aria-selected="false">Documentation</a>
        </li>
        @endif
      </ul>  
    </div>
    <div class="col-3 text-right">
      <a id="invsave">Save</a>
    </div>
  </div>

  <div class="tab-content">
    <div class="tab-pane active" id="main" role="tabpanel" aria-labelledby="main-tab">
      <div class="row">
        <div class="col">
          @include('export.parts.main')
          @include('export.parts.profile')
          @include('export.parts.amount')
        </div>
        @if($inv->dom < 3)
        <div class="col">
          @include('export.parts.customs')
          {{--  @include('export.booking.view') --}}
          @include('export.parts.book')
          @include('export.parts.bl')
        </div> 
        @endif
      </div>
    </div>
    <div class="tab-pane" id="items" role="tabpanel" aria-labelledby="items-tab">
      @include('export.pages.items')
    </div>
    @if($inv->creditid)
    <div class="tab-pane" id="credit" role="tabpanel" aria-labelledby="credit-tab">
      @include('export.pages.credit')
    </div>
    @endif
    @if($inv->bookid)
    <div class="tab-pane" id="book" role="tabpanel" aria-labelledby="books-tab">
      @include('export.pages.booking')
    </div>
    <div class="tab-pane" id="containers" role="tabpanel" aria-labelledby="containers-tab">
      @include('export.pages.container')
    </div>
    @endif
    @if($inv->bookid && $inv->creditid)
    <div class="tab-pane" id="document" role="tabpanel" aria-labelledby="document-tab">
      @include('export.pages.document')
    </div>
    @endif
  </div>    
</div>
@include('partials.modal')
@endsection

@section('script')
<script>
  $(document).ready(function() {
    $('.part').css('margin-bottom','10px');

    $('.title').on('click', function() {
      $(this).next('div.body').toggle();
    });
    
    $('textarea').each(function() {
      $lns = $(this).val().split(/\r*\n/).length;
      if (! $lns) {$lns = 4;}
      $(this).attr("rows", $lns);
    });

    $('#pointid').on('click', function() {
      var invid = $('#invoice').attr('invid');
      var url = "{{ url('/export/point') }}" + '/' + invid;
      $.ajax({
        method : "GET",
        url : url
      }).done(function (data) {
        $('#ModalLongTitle').html('Point ID');
        $('#ModalBody').empty().html(data);
        $('#ModalLong').modal('show');
        $('#btnsave, #btnupdate').hide();
        $('.pointrow').on('click', function() {
          var pointid = $(this).attr('data-id');
          url = "{{ url('update/inv') }}" + "/pointid/" + invid + "/" + pointid;
          $.ajax({
            type: "GET",
            url : url
          }).done(function() {
            location.reload();
          });
        });
      });
    });

    $('.datarow').on('click', function() {
      var invid = $('#invoice').attr('invid');
      var packid = $(this).attr('data-id');
      var url = "{{ url('/export/item') }}" + '/' + invid + '/' + packid;
      $.ajax({
        method : "GET",
        url : url
      }).done(function (data) {
        $('#ModalLongTitle').html('Packing List Item');
        $('#ModalBody').empty().html(data);
        $('div').removeClass('modal-lg');
        $('#ModalLong').modal('show');
        $('#btnsave, #btnupdate').show();
        if (packid) {
          $('#btnsave').hide();
        } else {
          $('#btnupdate').hide();
        }
        $('#btnsave, #btnupdate').click(function() {
          $('form#packingitem').submit();
          $('#item-tab').click();
//          packingsubmit();
        });
      })
    });
    
    $('#creditcode').on('keydown', function() {
      editcredit(0);
      return false;
    });

    $('#newbook').on('change', function() {
      var num = $(this).val();
      if (confirm('Add Booking No. '+num+' ?')) {
        var data = {'invid':$('#invoice').attr('invid'),'booknum':num,'_token':"{!! csrf_token() !!}"};
        console.log(data);
        $.ajax({
          type : "POST",
          url : "{{ url('booking/add') }}",
          data : data
        }).done(function(data) {
          location.reload();
        });
      }
    });

////////////////////Credit Page///////////////////    
    $('#creditid').on('change', function() {

      if( $(this).val() == -1) {
        var quest = "Do you want to duplicate this credit ?";
        var crid = $('#creditid').attr("origin");
      } else {
        var quest = "Do you really want to change Credit/Payment reference ?";
        var crid = 0;
      }
      if (confirm(quest)) {
        editcredit(crid);
      };
    });

  }); // end Ready() 
  function editcredit(crid) {
    var arid = $('#creditid').attr("arid");
    var url = "{{ url('credit/create') }}" + '/' + crid + '/' + arid;
    $.ajax({
      method : "GET",
      url : url
    }).done(function (data) {
      $('#ModalLongTitle').empty().html('New Credit/Payment');
      $('#ModalBody').empty().html(data);
      $('div').removeClass('modal-lg');
      $('#ModalLong').modal('show');
      $('#btnsave').show();
      $('#btnupdate').hide();
      $('#btnsave').click(function() {
        //$('form#newcredit').submit();
        savecredit();
      });
    });
  }

  function savecredit() {
    $.ajax({
      type : "POST",
      url : "{{ url('credit/save') }}",
      data : $('form#newcredit').serialize()
    }).done(function(newdata) {
      location.reload();
    });
  }
  function packingsubmit() {
    $.ajax({ 
      type: "GET",
      url : "{{ url('/export/itemsave') }}",
      data : $('form#packingitem').serialize(),
      success : function(data) {
        alert('OK');
      }
    }).done(function() {
      alert('Done');
    });
  }

</script>
@endsection
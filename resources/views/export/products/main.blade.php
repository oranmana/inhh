@extends('layouts.app')
@section('content')
@php( $igroup = (isset($igroup) ? $igroup : '3151') )
@php( $itype = (isset($itype) ? $itype : '99') )
@php( $groups = array(2=>'Raw Material', 24=>'Packing Material', 3151=>'ASR Product', 3152=>'EMP Product', 336=>'TG Product') )
@php( $form2 = array(99=>'-All-',0=>'Normal',1=>'Semi',7=>'Semi-Declined',8=>'FG-Declined',9=>'SO'))
<div class="container">
<div class="h3">Sale Products Item</div>

<div class="row">
  <label class="col-1">Group</label>
  <select id="itemgroup" class="form-control col-5"> 
    @foreach($groups as $g=>$v)
    <option value="{!! $g !!}"{!! $g == $igroup ? " SELECTED" : "" !!}>{{ $v }}</option>
    @endforeach
  </select>

  <label class="col-1">Type</label>
  <select id="itemtype" class="form-control col-5">
    @foreach($form2 as $f=>$v)
    <option value="{!! $f !!}"{!! $f == $itype ? " SELECTED" : "" !!}>{{ $v }}</option>
    @endforeach
  </select>
</div>

<div id="bodytable">
{{-- @include('export.products.list', compact(['igroup','itype']) ) --}}
</div>
@include('partials.modal')
</div>
@endsection

@section('script')
<script>
  $(document).ready(function() {
    $('#itemgroup').on('change', function() {
      var group = $(this).val();
      var nosub = ['2','24','336'];
      console.log( nosub.indexOf(group));
      if ( nosub.indexOf(group) != -1 ) {
        $('#itemtype').val(99).prop('disabled',true);
      } else {
        $('#itemtype').prop('disabled',false);
      }
      waiting();
      refreshtable();
    });
    
    $('#itemtype').on('change', function() {
      filtertype();
    });

    refreshtable();
  });

  function waiting() {
    $('#bodytable').empty().html("<div class='mt-10 text-center'>Loading..<div>");
  }

  function refreshtable() {
    var igroup = $('#itemgroup').val();
    var itype = $('#itemtype').val();
    $.ajax({
      type:"GET",
      url : "{{ url('products') }}" + "/" + igroup + "/" + itype
    }).done(function(data) {
      $('#bodytable').empty().html(data);

      $('.item').on('click', function() {
        var n = $(this).attr('data-id');
        getcard(n);
      });
    });
  }
  
  function filtertype() {
    var n = $('#itemtype').val();
    if (n==99) {
      $('.item').show();  
    } else {
      $('.item').hide();
      $(".item[mat-type=" + n + "]").show();
    }
  }

  function getcard(item) {
      var addnew = (item == 0);
      $.ajax({
        type : "GET",
        url : "{{ url('product') }}" + "/" + item
      }).done(function(data) {
        $('#ModalLong').removeClass('modal-lg');
        $('#ModalLongTitle').html((addnew ? 'New ' : 'Edit ') + 'Product Item');
        $('#ModalBody').empty().html(data);
        // $(btnhide).addClass('hidden');
        // $(btnshow).removeClass('hidden');
        // $(btnshow).on('click', function() {
        //   $('form#doctype').submit();
        // });
    })
  }

</script>
@endsection

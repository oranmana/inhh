@extends('layouts.app')

@section('content')
@php( $can_add = $orgid && $typeid )
<div class="container" scr="docs.index">
  <div id="headbar">
    <h2 class="text-info"><a href="{{ url('/') }}/home">Document Center</a></h2> 
    <div class="float-left form-inline">

      <label for="rqyr" class="form-control btn-primary">Year </label> 
      <select id="rqyr" name="rqyr" class="form-control mr-2">
        @foreach($docyrs as $yr)
          <option value="{{ $yr->yr }}">{{ $yr->yr }}</option>
        @endforeach
      </select>

      <label for="rqmth" class="form-control btn-primary mth">Month </label> 
      <select id="rqmth" name="rqmth" class="form-control mth mr-2">
        @foreach($docmths as $mth)
          <option value="{{ $mth->mth }}">{{ $mth->name }}</option>
        @endforeach
      </select>

      <label for="rqorg" class="form-control btn-primary">Org </label> 
      <select id="rqorg" name="rqorg" class="form-control rqmenu mr-2">
        <option value="0">-Select-</option> 
        @foreach($orglist as $org)
          <option value="{{ $org->id }}"{!! ($org->id == $orgid ? ' selected' : '') !!}>{!! '- '.$org->FullName !!}</option>
        @endforeach
      </select>

      <label for="rqgrp" class="form-control btn-primary grp">Type </label> 
      <select id="rqgrp" name="rqgrp" class="form-control rqmenu mr-2 grp">
        <option value="0">-Select-</option> 
        @foreach($doctypes as $type)
            <option value="{{ $type->id }}">{{ $type->id}}:{{$type->name }}</option>
        @endforeach
      </select>

      <label for="rqpp" class="form-control btn-primary pp">Category </label> 
      <select id="rqpp" name="rqpp" class="form-control rqmenu pp">
        <option value="0">-All-</option> 
        @foreach($pptypes as $pp)
            <option value="{{ $pp->id }}">{{$pp->id}}:{{ $pp->name }}</option>
        @endforeach
      </select>

    </div> 
    <div class="float-right">
      <div class="form-inline">
        @if ($can_add)
        <a id="btnadd" data-toggle="modal" data-target="#ModalLong" class="btn btn-sm">Add</a> 
        @endif
      </div>
    </div>
  </div>
  <div id="resdocs">
  </div>
</div>
@include('partials.modal')

@endsection

@section('script') 
<script>
  $(document).ready(function() {
    if ($('#rqgrp').val()==0) $('.pp').hide();
    if ($('#rqorg').val()==0) $('.grp').hide();
    
    $('#rqorg').on('change', function() {
      var orgid = $(this).val();
      if( orgid == 0 ) {
        $('.grp').hide();
      } else {
        $('.grp').show();
      }
      getdoclist();
    });

    $('#rqgrp').on('change', function() {
      if( $(this).val() == 9244 ) {
        $('.pp').show();
      } else {
        $('.pp').hide();
      }
      getdoclist();
    });

    $('#rqpp').on('change', function() {
      getdoclist();
    });

  }); // end of document.ready

  function getdoclist() {
    var rqyr = $('#rqyr').val();
    var rqmth = $('#rqmth').val();
    var rqorg = $('#rqorg').val();
    var rqdoc = $('#rqgrp').val();
    if (rqdoc==9244) {
      var rqdoc = $('#rqpp').val();
    }
    // console.log(rqyr, rqorg, rqdoc);
    if (rqyr > 0 && rqorg > 0 && rqdoc > 0) {
      $('#btnadd').off('click');
      $(this).addClass('btn-primary');
      $('#btnadd').on('click', function() {
        adddoc();
      });

      $.when( $('#resdocs').empty() ).done(function() {
        console.log('in');
        $.ajax({
          method : "GET",
          url : "{{ url('/docs') }}" + "/" + rqmth + "/" + rqorg + "/" + rqdoc
        }).done( function(res) {
          $('#resdocs').html(res);
          arrangehead();
        });
      });
    } else {
      $('#btnadd').removeClass('btn-primary');
      $('#btnadd').on('click', function() {
        alert('Please select full header');
      });
    }
  }

    function adddoc() {
      $.ajax({
        method : "GET",
        url : "{{ url('/doc/add') }}" + "/" + rqmth + "/" + rqorg + "/" + rqdoc
      }).done( function(res) {
        $('#ModalTitle').empty().html('Add new document');
        $('#modalLong').empty().html(res);
        $('#btnsave').text('Add').on('click', function() {
          $('form#adddoc').submit();
        });
      });
    }

</script>
@endsection
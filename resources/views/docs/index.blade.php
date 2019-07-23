@extends('layouts.app')

@section('content')
@php($pp = ($typeid == 9244) )
@php( $can_add = $orgid && ($pp ? $ppid : $typeid) )
<div class="container" scr="docs.index">
  <div id="headbar">
    <h2 class="text-info"><a href="{{ url('/') }}/home">Document Center</a></h2> 
    <div class="float-left form-inline">
      <label for="rqdate" class="form-control btn-primary">Year </label> 
      <select id="rqyr" name="rqyr" class="form-control rqmenu mr-2">
        @foreach($docyrs as $yr)
          <option value="{{ $yr->yr }}"{!! ($yr->yr == $year ? " SELECTED" : '') !!}>{{ $yr->yr }}</option>
        @endforeach
      </select>

      <label for="rqorg" class="form-control btn-primary">Org </label> 
      <select id="rqorg" name="rqorg" class="form-control rqmenu mr-2">
        <option value="0">-All-</option> 
        @foreach($orglist as $org)
          <option value="{{ $org->id }}"{!! ($org->id == $orgid ? " SELECTED" : '') !!}>{!! ($org->ref < 3 ? '<strong>' : '- ') . $org->name . ($org->ref==2 ? ' Dpt.</strong>' : '') !!}</option>
        @endforeach
      </select>

      <label for="rqgrp" class="form-control btn-primary">Type </label> 
      <select id="rqgrp" name="rqgrp" class="form-control rqmenu mr-2">
        <option value="0">-All-</option> 
        @foreach($doctypes as $type)
            <option value="{{ $type->id }}"{!! ($type->id == $typeid ? " SELECTED" : '') !!}>{{ $type->name }}</option>
        @endforeach
      </select>
      
      <label for="rqpp" class="form-control btn-primary pp">Category </label> 
      <select id="rqpp" name="rqpp" class="form-control rqmenu pp">
        <option value="0">-All-</option> 
        @foreach($pptypes as $pp)
            <option value="{{ $pp->id }}"{!! ($pp->id == $ppid ? " SELECTED" : '') !!}>{{ $pp->name }}</option>
        @endforeach
      </select>

    </div> 
    <div class="float-right">
      <div class="form-inline">
        @if ($can_add)
        <a id="btnupload" data-toggle="modal" data-target="#ModalLong" class="btn btn-sm">Add {!! $typeid .'/'.$ppid !!}</a> 
        @endif
      </div>
    </div>
  </div>

  <table class="table table-sm table-hover table-striped">
    <thead>
      <tr class="bg-info">
        <td>No.</td>
        <td>Date</td>
        <td>Subject</td>
        <td>From</td>
        <td>To</td>
        <td>{!! ($pp ? 'Type' : 'Ref') !!}</td>
        <td>State</td>
      </tr>
    </thead>
    <tbody>
      @foreach($docs as $doc)
      @php($files = count($doc->FilesUploaded) )
      <tr data-id="{{ $doc->id }}" class="docuploadarea">
        <td nowrap>{{ $doc->DocNum }}</td>
        <td nowrap>{{ edate($doc->indate) }}</td>
        <td title="{{ $files }}">{!! 
          "<a class='uploadeddoc" . ($files > 0 ? "'" : " text-info'") 
          . " data-toggle='modal' data-target='#ModalLong'>". $doc->name . "</a>" 
        !!}</td>
        <td>{!! ($doc->user ? $doc->user->name : $doc->docfrom) !!}</td>
        <td>{!! $doc->docto !!}</td>
        <td class=small>{!! ($pp ? $doc->doctype->code : $doc->ref) !!}</td>
        <td>{!! $doc->state !!}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $docs->links('pagination') }}
</div>
<div class="hidden" style="display:none">
  <form id="uploadform" method="POST" action="{{ url('doc/upload') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="text" name="docid" id="docid">
    <input type="file" name="filedata" id="filedata">
  </form>
</div>
@include('partials.modal')
@endsection

@section('script') 
<script>
  $(document).ready(function() {

    $("html, tr").on("drop file-drop", function(event) { 
      event.preventDefault(); 
      event.stopPropagation();
    });

    $('.rqmenu').on('change', function() {
      let url = "{{ url('/doc/list') }}" + '/' +  $('#rqyr').val() + '/' + $('#rqorg').val() + '/' + $('#rqgrp').val() + '/' + $('#rqpp').val();
      window.location.href = url;
    });
    if ($('#rqgrp').val() != 9244) {
      $('.pp').hide();
    } else {
      $('.pp').show();
    }

    $('.uploadeddoc').on('click',function() {
      let docid = $(this).closest('tr').attr('data-id');
      $('#ModalBody').empty();
      $.ajax({
        method:"GET",
        url : "{{ url('/doc/card') }}" + '/' + docid
      }).done(function(data) {
        $('#ModalLongTitle').empty().html('Document Library #' + docid);
        $('#ModalBody').empty().html(data);
        $('#btnsave').hide();
        setdroparea();
      });
      return true;
    });
    setdroparea();
  });

  function setdroparea() {
    $(".docuploadarea").on('dragover dragenter', function() {
      $(this).addClass('bg-secondary text-white');
    })
    .on('dragleave dragend drop', function() {
      $(this).removeClass('bg-secondary text-white');
    })
    .on('drop', function(event) {
      concole.log(event.originalEvent.dataTransfer.files.length);
      event.preventDefault();
      event.stopPropagation();
      
      var docid = $(this).attr('data-id');
      var files = event.originalEvent.dataTransfer.files;
      $('#uploadform > #docid').val(docid);
      $('#uploadform > #filedata').val(files[0]);
     
      console.log('upload prepared ');
    });
  }
  function uploadData(formdata){
    $.ajax({
      url: "{{ url('doc/upload') }}",
      type: 'post',
      data: formdata,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function(response){
          //addThumbnail(response);
        alert('File Successfully Uploaded')
      }
    });
  }    

</script>
@endsection

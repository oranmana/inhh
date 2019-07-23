@extends('layouts.app')
@section('content')
<table class="table">
  <tr>
    <td class="w-50">
      @include('docs.typelist', ['par' => 3989])
    </td>
    <td class="w-50">
    @include('docs.typelist', ['par' => 5078])
    </td>
  </tr>
</table>
@include('partials.modal')
@endsection

@section('script')
<script>
  $(document).ready(function() {
    $('.doctype').on('click', function() {
      editdoctype($(this).attr('data-id'));
    });
  });
  function editdoctype(typeid) {
    $.ajax({
      type:"GET",
      url : "{{ url('doctype/edit') }}" + "/" + typeid
    }).done(function(data) {
      var addnew = (typeid == 3989 || typeid == 5078);
      var btnhide = (addnew ? $('#btnupdate') : $('#btnsave') );
      var btnshow = (addnew ? $('#btnsave') : $('#btnupdate') );
      $('#ModalLong').removeClass('modal-lg');
      $('#ModalLongTitle').html((addnew ? 'New ' : 'Edit ') + 'Document Type');
      $('#ModalBody').empty().html(data);
      $(btnhide).addClass('hidden');
      $(btnshow).removeClass('hidden');
      $(btnshow).on('click', function() {
        $('form#doctype').submit();
      });
    });
  }
</script>
@endsection

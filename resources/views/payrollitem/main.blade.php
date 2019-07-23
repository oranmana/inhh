@extends('layouts.app')
@section('style')
<style>
  .divwin {
    height: 400px;
    overflow-y: scroll;
  }
</style>
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div id="leftside" class="col-4">
      <div class="h3">Payroll Items</div>
      <div id="payitems" class="divwin col">
      @include( 'payrollitem.list',['parid' => 3782, 'level' => 0] )
      </div>
    </div>
    <div id="body" class="col-7" >
    </div>
  </div>

  @include('partials.modal')
</div>
@endsection

@section('script')
<script>
  $(document).ready(function() {
    $('.item').on('click', function() {
      getitem( $(this).attr('data-id') )
    });
  });

  function getitem(id) {
    console.log('goto ' + id);
    $.ajax({
      type : "GET",
      url : "{{ url('payitem/card') }}" + "/" + id
    }).done(function(data) {
      $('#body').empty().html(data);      
    });
  }
</script>
@endsection

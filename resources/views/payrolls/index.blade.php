@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="h2">{{ $yr }} Payroll</div>
    <div class="float-right">
      <a id="addpayroll">+ Add</a>
    </div>
  </div>
  <table class="table table-sm table-hover">
    <thead>
      <tr class="bg-info" align="center">
        <td>No.</td>
        <td>Month</td>
        <td>Group</td>
        <td>Paid On</td>
        <td>Income</td>
        <td>Deduct</td>
        <td>Net Paid</td>
        <td>State</td>
      </tr>
    </thead>
    <tbody>
      @php($ln=1)
      @foreach($payrolls as $payroll)
        @php($income = $payroll->income->where('plus',1)->sum('amount'))
        @php($deduct = $payroll->income->where('plus',0)->sum('amount'))
        @php($net = $income - $deduct)
      <tr data-id="{{ $payroll->id }}" onclick="window.location='{!! url('/payroll'). '/' . $payroll->id !!}';"  align="center">
        <td>{{ $ln++ }}) </td>
        <td>{!! $payroll->mth .'-'. $payroll->num !!}</td>
        <td>{!! $payroll->paygrp->ref !!}</td>
        <td>{!! edate($payroll->payon) !!}</td>
        <td align="right">{!! fnum( $income,2 ) !!}</td>
        <td align="right">{!! fnum( $deduct,2 ) !!}</td>
        <td align="right">{!! fnum( $net,2 ) !!}</td>
        <td>{!! $payroll->state !!}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@include('partials.modal')
@endsection

@section('script')
<script>
  $(document).ready(function() {

    $('#addpayroll').on('click', function() {

      $.ajax({
        type:"GET",
        url : "{{ url('payroll/add') }}"
      }).done(function(data) {
        $('#ModalLongTitle').empty().html('Create New Payroll Payment');
        $('#ModalBody').empty().html(data);
        $('#btnupdate').remove(); 
        $('#ModalLong').modal('show');
        $('#btnsave').html('Create Payroll')
          .on('click', function() {
            addpayroll();
          });
      });
    });
  });

  function addpayroll() {
    $.ajax({
      type:"POST",
      data : $('form#newpayroll').serialize(),
      url : "{{ url('payroll/create') }}"
    }).done( function(data)  {
      console.log(data);
    });
  }

</script>
@endsection

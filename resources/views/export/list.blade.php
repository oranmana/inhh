@extends('layouts.app')

@section('content')
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
      <div class="form-inline">
        <a id="btnupload" class="btn btn-sm">Main</a> 
        <a id="btnupload" class="btn btn-sm">Schedule</a> 
      </div>
    </div>
  </div>
  <table class="table table-sm table-hover table-striped">
    <thead>
      <tr class="bg-info text-center">
        <td>InvNum</td>
        <td>S/O</td>
        <td>Order</td>
        <td>Customer</td>
        <td>Port</td>
        <td>Price</td>
        <td>20'</td>
        <td>40'</td>
        <td>USD</td>
        <td>Amount</td>
        <td>B/L Date</td>
        <td>PIC</td>
        <td>State</td>
      </tr>
    </thead>
    <tbody>
      @foreach($invs as $inv)
      @php( $customer = $inv->point->dir ?? 0 )
      @if(! $inv->point->port)
        {!! dd($inv->point) !!}
      @endif
      <tr data-id="{{ $inv->id }}" class="datarow" >
        <td nowrap>{{ $inv->invnumber }}</td>
        <td nowrap>{{ $inv->sonum }}</td>
        <td nowrap>{{ $inv->ponum }}</td>
        <td>{!! $inv->point->dir->nm !!}</td>
        <td>{!! $inv->point->port ? $inv->point->port->name  : $inv->point->portid !!}</td>
        <td>{!! $inv->priceterm->name !!}</td>
        <td align="right">{!! fnum($inv->all20,0,0,1) !!}</td>
        <td align="right">{!! fnum($inv->all40,0,0,1) !!}</td>
        <td align="right">{!! $inv->currency->code !!}</td>
        <td align="right">{!! fnum($inv->amt,2) !!}</td>
        <td nowrap>{{ edate($inv->bldate) }}</td>
        <td>{!! $inv->pic->emp->nm !!}</td>
        <td>{!! $inv->statename !!}</td>
      </tr>
      @endforeach
      <tr class="small bg-info" >
        <td colspan=6 align="center">TOTAL</td>
        <td align="right">{!! fnum($invs->sum('q20')+$invs->sum('q20h'),0,0,1) !!}</td>
        <td align="right">{!! fnum($invs->sum('q40')+$invs->sum('q40h'),0,0,1) !!}</td>
        <td align="right">&nbsp;</td>
        <td align="right">{!! fnum($invs->sum('amt'),2) !!}</td>
        <td colspan=3>&nbsp;</td>
      </tr>
    </tbody>
  </table>
</div>
@include('partials.modal')
@endsection

@section('script') 
<script>
  $(document).ready(function() {

    $('#rqyr, #rqmth, #rqgrp').on('change', function() {
      let url = "{{ url('sales') }}" + '/' + $('#rqgrp').val() + '/' + $('#rqyr').val() + '/' + $('#rqmth').val();
      window.location = url;
    });

    $('.datarow').on('click',function() {
      url = "{{ url('sale') }}" + '/' + $(this).attr('data-id') ;
      window.location = url;
    });

  });
</script>
@endsection

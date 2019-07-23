@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="h2 col">Payroll {!! $payroll->mth .'-'. $payroll->num !!}</div>
    <div class="col float-right dropdown text-right">
      <button class="btn dropdown-toggle" type="button" id="PayrollDropMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Menu</button>
      <div class="dropdown-menu" aria-labelledby="PayrollDropMenu">
        <div class="dropdown-item"><a>View</a></div>
        <div class="dropdown-item"><a>Close</a></div>
        <div class="dropdown-item"><a>Invoice</a></div>
      </div>
    </div>
  </div>

  <form>
    <input type="hidden" value="{{ $payroll->id }}">
    <div>
      <label class="mr-3">Month</label>
      <input type="text" value="{{ $payroll->mth }}" class="col-1 text-center">
      <label class="ml-3 mr-3">/</label>
      <input type="text" value="{{ $payroll->num }}" class="col-1 text-center">
      <label class="ml-3 mr-3">Group</label>
      <select class="col-1">
      @foreach(App\Models\Common::EmpGroup()->get() as $cm)
        <option value="{{ $cm->id }}"{{ ($cm->id == $payroll->grp_id ? " selected" : '' )}}>{{ $cm->ref }}</option>
      @endforeach
      </select>
      <label class="ml-3 mr-3">Date of Payment</label>
      <input type="text" value="{{ edate($payroll->payon,3) }}" class=" text-center">
    </div>
    <div class="row">
      <label class="ml-3 mr-3">Wage</label>
      <input type="checkbox" {{ $payroll->wageonly ? ' checked' : '' }}>
      <label class="ml-3 mr-3">Period</label>
      <input type="text" value="{{ edate($payroll->wagefor) }}" class=" text-center">
      <label class="ml-3 mr-3">To</label>
      <input type="text" value="{{ edate($payroll->wageto) }}" class=" text-center">
      @if( ! $payroll->wageonly )
      <label class="ml-3 mr-3">OT Period</label>
      <input type="text" value="{{ edate($payroll->otfor) }}" class=" text-center">
      <label class="ml-3 mr-3">To</label>
      <input type="text" value="{{ edate($payroll->otto) }}" class=" text-center">
      @endif
    </div>
  </form>
  <table class="table table-sm table-hover">
    <thead>
      <tr class="bg-info trhead" align="center">
        <td>No.</td>
        <td>Employee</td>
        <td>BA</td>
        <td>CC</td>
        @foreach($payitems as $head)
          @if($head->plus)
            <td title="{{ $head->item->name }}">{{ $head->item->code }}</td>
          @endif
        @endforeach
        <td>Income</td>
        @foreach($payitems as $head)
          @if(! $head->plus)
            <td title="{{ $head->item->name }}">{{ $head->item->code }}</td>
          @endif
        @endforeach
        <td>Deduction</td>
        <td>TOTAL</td>
      </tr>
    </thead>
    <tbody>
      @php($ln=1)
      @php($ba=0)
      @foreach($pays as $pay)
<!-- Check Sub-total       -->
      @if($pay->erp_ba != $ba) 
        @if($ba > 0) 
          @php( $income = $payroll->payamts()->where('erp_ba',$ba)->where('plus',true)->sum('amount') )
          @php( $deduct = $payroll->payamts()->where('erp_ba',$ba)->where('plus',false)->sum('amount') )
        <tr data-id="{{ $ba }}" align="right" class="bg-secondary subtotal">
          <td align="center" colspan=4>Sub-Total : BA {{$ba}}</td>
        
          @foreach($payitems->where('plus',true) as $head)
            @php( $item = $payroll->payamts()->where('erp_ba',$ba)->where('item_id', $head->item_id)->sum('amount') )
            <td class="small">{!! fnum( ($item != null ? $item : 0),2) !!}</td>
          @endforeach
          <td>{!! fnum($income,2) !!}</td>
        
          @foreach($payitems->where('plus',false) as $head)
            @php( $item = $payroll->payamts()->where('erp_ba',$ba)->where('item_id', $head->item_id)->sum('amount') )
            <td class="small">{!! fnum( ($item != null ? $item : 0),2) !!}</td>
          @endforeach
          <td>{!! fnum($deduct,2) !!}</td>

          <td><b>{!! fnum($income - $deduct,2) !!}</b></td>
        </tr>
        @endif
        @php($ba = $pay->erp_ba)
      @endif
<!-- End Check Sub-total       -->

      <tr data-id="{{ $pay->id }}" align="right" class="emp{{ $ba }}">
        <td>{{ $ln++ }}) </td>
        <td align="left" nowrap>{!! $pay->emp->thname !!}</td>
        <td align="center">{!! $pay->erp_ba !!}</td>
        <td align="center">{!! $pay->erp_cc !!}</td>

        @foreach($payitems->where('plus',true) as $head)
          @php( $item = $pay->payamts()->where('item_id', $head->item_id)->first() )
          <td class="small">{!! fnum( ($item != null ? $item->amount : 0),2) !!}</td>
        @endforeach
        <td>{!! fnum($pay->allincome,2) !!}</td>

        @foreach($payitems->where('plus',false) as $head)
          @php( $item = $pay->payamts()->where('item_id',$head->item_id)->first() )
          <td class="small">{!! fnum( ($item != null ? $item->amount : 0),2) !!}</td>
        @endforeach
        <td>{!! fnum($pay->alldeduct,2) !!}</td>

        <td><b>{!! fnum($pay->allincome - $pay->alldeduct,2) !!}</b></td>
      </tr>
      @endforeach
<!-- Sub-Total -->
          @php( $income = $payroll->payamts()->where('erp_ba',$ba)->where('plus',true)->sum('amount') )
          @php( $deduct = $payroll->payamts()->where('erp_ba',$ba)->where('plus',false)->sum('amount') )
        <tr data-id="{{ $ba }}" align="right" class="bg-secondary subtotal">
          <td align="center" colspan=4>Sub-Total : BA {{$ba}}</td>
        
          @foreach($payitems->where('plus',true) as $head)
            @php( $item = $payroll->payamts()->where('erp_ba',$ba)->where('item_id', $head->item_id)->sum('amount') )
            <td class="small">{!! fnum( ($item != null ? $item : 0),2) !!}</td>
          @endforeach
          <td>{!! fnum($income,2) !!}</td>
        
          @foreach($payitems->where('plus',false) as $head)
            @php( $item = $payroll->payamts()->where('erp_ba',$ba)->where('item_id', $head->item_id)->sum('amount') )
            <td class="small">{!! fnum( ($item != null ? $item : 0),2) !!}</td>
          @endforeach
          <td>{!! fnum($deduct,2) !!}</td>

          <td><b>{!! fnum($income - $deduct,2) !!}</b></td>
        </tr>
<!-- Grand Total -->
      @php( $income = $payroll->payamts()->where('plus',true)->sum('amount') )
      @php( $deduct = $payroll->payamts()->where('plus',false)->sum('amount') )
      <tr align="right" class="bg-info">
        <td align="center" colspan=4>GRAND TOTAL</td>
        
        @foreach($payitems->where('plus',true) as $head)
          @php( $item = $payroll->payamts()->where('item_id', $head->item_id)->sum('amount') )
          <td class="small">{!! fnum( ($item != null ? $item : 0),2) !!}</td>
        @endforeach
        <td>{!! fnum($income,2) !!}</td>
      
        @foreach($payitems->where('plus',false) as $head)
          @php( $item = $payroll->payamts()->where('item_id', $head->item_id)->sum('amount') )
          <td class="small">{!! fnum( ($item != null ? $item : 0),2) !!}</td>
        @endforeach
        <td>{!! fnum($deduct,2) !!}</td>

        <td><b>{!! fnum($income - $deduct,2) !!}</b></td>
      </tr>
    </tbody>
  </table>
</div>
@endsection

@section('script')
<script>
  $(document).ready(function() {
    $('.subtotal').on('dblclick', function() {
      $('.emp'+$(this).attr('data-id')).toggle();
    })
    $("tr[class^='emp']").hide();
  });
</script>
@endsection
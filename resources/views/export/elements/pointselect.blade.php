@php( $inv = App\Models\mkinvoice::find($invid) )
@php( $customer = ($invid ? App\Models\mkcustomer::find($inv->point->dirid) : 0) )
@php( $customerid = ($invid ? $customer->id : 0))
@php( $points = ($customerid ? App\Models\mkpoint::where('dirid', $customer->id)->orderBy('dirid')->orderBy('portid')->orderBy('pricetermid')->orderBy('paymentid','desc') : 0 ) )
<div class="container">
  <input id="invid" type="hidden" value="{{ $invid }}">
  <label class="col-2">Customer</label>
  @if ($customerid)
  <input type='text'  class="col-2" id="customerid" data-id="{{ $customer->id }}" value="{{ $customer->nm }}">
  <label  class="col-2">Name</label>
  <input  class="col-5" type='text' id="customername" value="{{ $customer->name }}">
  <table class="table table-hover">
    <thead>
      <tr class="table-row bg-secondary text-white">
        <th class="table-cell text-center">Code</th>
        <th class="table-cell text-center">Customer</th>
        <th class="table-cell text-center">Port of Discharge</th>
        <th class="table-cell text-center">Price Term</th>
        <th class="table-cell text-center">Payment</th>
    </tr>
    </thead>
    <tbody>
      @foreach($points->get() as $point) 
      <tr class="table-row pointrow " data-id="{{ $point->id }}">
        <td class="text-center">{!! $point->code ?? '' !!}</td>
        <td class="table-cell">{{ $point->dir->nm ?? ''}}</td>
        <td class="table-cell">{{ $point->port->name ?? ''}}</td>
        <td class="table-cell text-center">{{ $point->priceterm->name ?? ''}}</td>
        <td class="table-cell text-center" title="{{ $point->payterm->name }}">{{ $point->payterm->code ?? ''}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
    @php( $customers = App\Models\mkcustomer::where('nm','>','')->orderby('nm') )
    <select name="newcustomer" id="newcustomer" class="form-control">
      <option value="0">-Select Customer-</option>
      @foreach($customers->get() as $cus)
      <option value="{{ $cus->id }}">{{ $cus->nm . ' : ' . $cus->name  }}</option>
      @endforeach
    </select>
  @endif
</div>
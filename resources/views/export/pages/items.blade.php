@php($items = $inv->items)
<div class="container border rounded part" id="partitems">
  <h3 class="title">Packing List</h3>
  <div class="body">
    <table class="table table-sm">
      <thead>
        <tr class="text-center">
          <th>No.</th>
          <th>Item</th>
          <th>Name</th>
          <th>Package</th>
          <th>Quantity</th>
          <th>Unit Price</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
      @php($ln=1)
      @php($SaleKgs = 0)
      @php($SaleAmount = 0)
      @php($uom = '')
        @foreach($items as $item)
        @php( $kgs = $item->SaleKgs / ($item->unitprice > 100 ? 1000 : 1) )
        @php( $amount = $item->salequantity * $item->unitprice )
        <tr data-id="{{ $item->id }}" class="datarow text-right">
          <td>{{ $ln++ }}.</td>
          <td class="text-left">{{ $item->productcode }}</td>
          <td class="text-left">{{ $item->productname  }}</td>
          <td>{!! ($item->size->NetKgs ?? '') .'x' . fnum($item->packquantity,0) 
            . ( ($item->packquantity != $item->loadquantity) && ($item->loadquantity > 0) ? '/'. $item->loadquantity : '') 
            . ' ' . $item->size->packname !!}</td>
          <td>{!! fnum($item->salequantity,3) . ' ' . $item->productuom !!}</td>
          <td>{{ fnum($item->unitprice,2) }}</td>
          <td>{{ fnum($amount,2) }}</td>
          @php($SaleKgs += $item->salequantity)
          @php($SaleAmount += $amount)
          @php($uom = ($uom ? $uom : $item->productuom) )
        </tr>
      @endforeach
      @if($ln > 1)
        <tr class="text-right bg-info">
          <td colspan="4" class="text-center">TOTAL {!! ($ln-1) . " Items" !!}</td>
          <td>{{ fnum($SaleKgs,3) . ' ' . $uom}}</td>
          <td colspan=2>{{ fnum($SaleAmount,2) }}</td>
        </tr>
      @endif
      </tbody>
    </table>
  </div>
</div>


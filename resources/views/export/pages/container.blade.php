@php($boxes = $inv->boxes)
<div class="container border rounded part" id="pagecontainer">
  <h3 class="title">Container List</h3>
  <div class="body">
    <table class="table table-sm">
      <thead>
        <tr class="text-center">
          <th>No.</th>
          <th>#Container</th>
          <th>Size</th>
          <th>#Seal</th>
          <th>In</th>
          <th>Load</th>
          <th>Out</th>
          <th>Remakr</th>
        </tr>
      </thead>
      <tbody>
      @php($ln=1)
        @foreach($boxes as $box)
        <tr data-id="{{ $box->id }}" class="datarow text-right">
          <td>{{ $ln++ }}.</td>
          <td class="text-left">{{ $box->connum }}</td>
          <td class="text-left">{{ $box->size->code }}</td>
          <td class="text-left">{{ $box->sealnum }}</td>
          <td>{!! edate($box->datein,1) !!}</td>
          <td>{!! edate($box->dateload,1) !!}</td>
          <td>{!! edate($box->dateout,1) !!}</td>
          <td class="text-left">{{ $box->remark }}</td>
        </tr>
      @endforeach
      @if($ln > 1)
        <tr class="text-right bg-info">
          <td colspan="4" class="text-center">TOTAL {!! ($ln-1) . " Items" !!}</td>
        </tr>
      @endif
      </tbody>
    </table>
  </div>

  <h3 class="title">Loaded Items</h3>
  <div class="body">
    <table class="table table-sm">
      <thead>
        <tr class="text-center">
          <th rowspan=2>#Container</th>
          <th rowspan=2>Item</th>
          <th rowspan=2>Name</th>
          <th rowspan=2>Size</th>
          <th colspan=2>Quantity</th>
          <th rowspan=2>Load Weight</th>
        </tr>
        <tr class="text-center">
          <th>Order</th>
          <th>Load</th>
        </tr>
      </thead>
      <tbody>
      @php($ln=1)
      @php($orderqty = 0)
      @php($loadqty = 0)
      @php($loadkgs = 0)
      @foreach($boxes as $box)
        @php($items = $box->items)
        @foreach($items as $item)
          @php( $order = $item->order )
          @php( $kgs = $item->quantity * $order->packsize )
          @php( $ln++ )
          <tr data-id="{{ $box->id }}" class="datarow">
            <td>{{ $item->box->connum }}</td>
            <td>{{ $order->productcode }}</td>
            <td>{{ $order->productname }}</td>
            <td class="text-right">{{ $order->packsize }}</td>

            <td class="text-right">{{ $order->packquantity }}</td>
            <td class="text-right">{{ $item->quantity }}</td>
            <td class="text-right">{{ fnum($kgs) }}</td>
          </tr>
          @php ($orderqty += $order->packquantity )
          @php ($loadqty += $item->quantity )
          @php ($loadkgs += $kgs )
        @endforeach
      @endforeach
      @if($ln > 1)
        <tr class="text-right bg-info">
          <td colspan="4" class="text-center">TOTAL {!! ($ln-1) . " Items" !!}</td>
          <td class="text-right">{{ fnum($orderqty) }}</td>
          <td class="text-right">{{ fnum($loadqty) }}</td>
          <td class="text-right">{{ fnum($loadkgs) }}</td>
        </tr>
      @endif
      </tbody>
    </table>
  </div>
</div>


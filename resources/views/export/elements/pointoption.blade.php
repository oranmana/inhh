@php( $points = ($customerid ? App\Models\mkpoint::where('dirid', $customerid)->orderBy('dirid')->orderBy('portid')->orderBy('pricetermid')->orderBy('paymentid','desc') : 0 ) )
<option value=0>-Select Point ID-</option>
@foreach($points->get() as $point) 
<option pod="{{ $point->port->name . ', ' .  $point->port->parent->name }}" prc="{{ $point->priceterm->name }}" pay=" {{ $point->payterm->name }}" value="{{ $point->id }}">{!! '[' . $point->code . '] ' . $point->port->name . ' ('.$point->priceterm->name.'/'.$point->payterm->code . ')' !!}</option>
@endforeach

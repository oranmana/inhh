@php( $countries = \App\Models\Country::OfCountry($parid)->where('sub', $levelid)->where('name','>','')->orderBy('name')->get() )
@php( $itemid = (isset($itemid) ? $itemid : 0) )
@php( $names = array(1=>'countryname', 2=>'seaportname',3=>'airportname') )

@foreach($countries as $country)
<option class="cname {{ $names[$levelid] }}" value="{!! $country->id !!}"{!! $country->id == $itemid ? " SELECTED" : "" !!}>{{ $country->name }} {!! $country->code ? '('.$country->code .')' : '' !!}</option>
@endforeach
<option class="cname  {{ $names[$levelid] }}" value="0">-New-</option>


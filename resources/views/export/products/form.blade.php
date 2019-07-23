{{-- $itemid --}}
@php( $item = \App\Models\sapitem::find($itemid) )
@php( $types = array('ROH'=>'Raw Material','FERT'=>'Finished Products','HALB'=>'Semi-Products','HAWA'=>'Trading Goods'))
@php( $bas = array('3100'=>'Common ASR', '3110'=>'ASR I 
(A,B)', '3120'=>'ASR II (C)', '3130'=>'FLR','3140'=>'SM' ))
@php( $plants = array('3000'=>'ASR', '3100'=>'FLR'))
@php( $vcs = array('3100'=>'R/M', '3500'=>'T/G','4901'=>'GEN','4902'=>'GEN2','4903'=>'GEN3','7000'=>'HALB','7900'=>'FERT','7901'=>'FERT-ASR Scrap','7902'=>'FERT-FLR Scrap') )
@php( $uoms = array(2=>'Kg',8504=>'MT',3=>'Ea') )
<form id="sapitem" method="POST" action="products/edit">
  {!! csrf_field() !!}
  <label>ID</label>
  <input type="text" class="form-control" name="itm_id"  value="{{ $item->itm_id ?? $itemid }}">

  <div class="row">
    <div class="col">
      <label>Code*</label>
      <input type="number" class="form-control"  name="code" value="{{ $item->itm_code ?? '' }}" required>
    </div>
    <div class="col">
      <label>Type*</label>
      <select name="type"  class="form-control">
      <option value="0">-Select-</option>
      @foreach($types as $code=>$value)
        <option value="{{ $code }}"{!! $code==$item->itm_type ?? 0 ? " SELECTED" : '' !!}>{{ $code }}:{{ $value }}</option>
      @endforeach
      </select>
    </div>
    <div class="col">
      <label>BA*</label>
      <select name="ba"  class="form-control">
      @foreach($bas as $code=>$value)
        <option value="{{ $code }}"{!! $code==$item->itm_ba ?? 3110 ? " SELECTED" : '' !!}>{{ $code }}:{{ $value }}</option>
      @endforeach
      </select>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label>Plant</label>
      <select name="plant"  class="form-control">
      <option value="0">-Select-</option>
      @foreach($plants as $code=>$value)
        <option value="{{ $code }}"{!! $code==$item->itm_plant ?? 3000 ? " SELECTED" : '' !!}>{{ $code }}:{{ $value }}</option>
      @endforeach
      </select>
    </div>
    <div class="col">
      <label>Value</label>
      <select name="vc"  class="form-control">
      <option value="0">-Select-</option>
      @foreach($vcs as $code=>$value)
        <option value="{{ $code }}"{!! $code==$item->itm_vc ?? 0 ? " SELECTED" : '' !!}>{{ $code }}:{{ $value }}</option>
      @endforeach
      </select>
    </div>
    <div class="col">
      <label>Declined*</label>
      <select name="dcl"  class="form-control">
        <option value="0">Normal, Not Declined</option>
        <option value="1"{!! $item->itm_dcl ?? 0 == 1 ? " SELECTED" : '' !!}>Declined</option>
      </select>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label>Name*</label>
      <input type="text" class="form-control"  name="name" value="{{ $item->itm_name ?? '' }}" required>
    </div>
    <div class="col">
      <label>Commercial Name</label>
      <input type="text" class="form-control"  name="name" value="{{ $item->itm_cname ?? '' }}">
    </div>
  </div>
  <div class="row">
    <div class="col">
      <label>Group Name 1</label>
      <input type="text" class="form-control"  name="p1" value="{{ $item->itm_p1 ?? '' }}" required>
    </div>
    <div class="col">
      <label>Group Name 2</label>
      <input type="text" class="form-control"  name="p2" value="{{ $item->itm_p2 ?? '' }}" required>
    </div>
    <div class="col">
      <label>Group Name 3</label>
      <input type="text" class="form-control"  name="p3" value="{{ $item->itm_p3 ?? '' }}" required>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <label>Packing Size</label>
      <input type="text" class="form-control" name="pk" value="{{ $item->itm_pk ?? 1 }}">
      </div>
    <div class="col">
      <label>UOM</label>
      <select name="uom"  class="form-control">
        <option value="0">-Select-</option>
        @foreach($uoms as $code=>$value)
          <option value="{{ $code }}"{!! $code==$item->i_uom ?? 0 ? " SELECTED" : '' !!}>{{ $value }}</option>
        @endforeach
      </select>
      </div>
  </div>
</form>
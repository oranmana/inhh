@php( $me = \App\Models\Country::findornew($itemid) )
@if( $itemid==0) 
  @php( $me->sub = $grp )
  @php( $me->par = $par )
@endif
<form id="countryform">
  {{ csrf_field() }}
  <input type="hidden" id='id' name="id" value="{{ $me->id ?? '' }}" class="form-control col-10">
  <input type="hidden" name="par" value="{{ $me->par ?? '' }}" class="form-control col-10">
  <div class="row">
    <label class="h3 col-6">{!! ($itemid==0 ? 'New ' : '') . ' Area Profile' !!}</label>
    <div class="text-right col-6">
      <input type="checkbox"{!! ($me->off ?? 0 > 0 ? '':  " checked") !!}> Active
    </div>
  </div>
  <div class="row">
    <label class="form-control col-2">Type</label>
    <select name="sub" class="form-control col-10">
      @php($types = array(0=>'Zone', 1=>'Country', 2=>'Sea/ICD Port', 3=>'Air Port') )
      @foreach($types as $code=>$text)
      <option value="{{ $code }}"{!! ($code*1 == $me->sub ? " selected" : '') !!}>{{ $text }}</option>
      @endforeach
    </select>
  </div>
  <div class="row">
    <label class="form-control col-2">Name</label>
    <input type="text" name="name" value="{{ $me->name ?? '' }}" class="form-control col-10">
  </div>
  <div class="row">
    <label class="form-control col-2">Code</label>
    <input type="text" name="code" value="{{ $me->code ?? '' }}" class="form-control col-10">
  </div>
  <div class="row">
    <label class="form-control col-2">Map Location</label>
    <input type="text" name="ref" value="{{ $me->ref ?? '' }}" class="form-control col-10">
  </div>

@if( $itemid==0) 
  <button id='btnsave' class="btn btn-info form-control">Save</button>
@else
  <button id='btnupdate' class="btn btn-primary form-control">Update</button>
@endif
</form>


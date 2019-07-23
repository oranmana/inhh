<form method="post" action="{{ url('asset/save') }}">
  {{ csrf_field() }}
  <input type="hidden" name="id" value="{{ $asset->id }}">

  <div class="row">
    <div class="col-md-3"><label>Item* </label></div>
    <div class="col-md-9">
      <input type="hidden" name="itemid" value="{{ $asset->itemid }}">
      <input type="text" name="itemid" list="itemlist" class="form-control" placeholder="Asset Item" value="{{ optional($asset)->item->name }}">
        <datalist id="itemlist">
        @foreach($assetitems as $item)
          <option data-id="{{ $item->id }}" value="{{ $item->name }}">
        @endforeach
        </datalist>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3"><label>Entry On* </label></div>
    <div class="col-md-9">
      <input type="text" id="indate" class="form-control switchtext" value="{{ edate(optional($asset)->indate) }}">
      <input type="date" name="indate" class="form-control switchtext" style="display:none;">
    </div>
  </div>

  <div class="row">
    <div class="col-md-3"><label>G/R No.* </label></div>
    <div class="col-md-9">
      <input type="text" name="indoc" class="form-control" value="{{ optional($asset)->indoc }}">
    </div>
  </div>

  <div class="row">
    <div class="col-md-3"><label>Description* </label></div>
    <div class="col-md-9">
    <textarea name="des" class="form-control" rows="2">{{ optional($asset)->des }}"</textarea>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3"><label>Brand* </label></div>
    <div class="col-md-9">
    <input type="text" name="brand" class="form-control" value="{{ optional($asset)->brand }}">
    </div>
  </div>

  <div class="row">
    <div class="col-md-3"><label>Model </label></div>
    <div class="col-md-9">
    <input type="text" name="model" class="form-control" value="{{ optional($asset)->model }}">
    </div>
  </div>

  <div class="row">
    <div class="col-md-3"><label>Serial Number </label></div>
    <div class="col-md-9">
    <input type="text" name="serial" class="form-control" value="{{ optional($asset)->serial }}">
    </div>
  </div>

  <div class="row">
    <div class="col-md-3"><label>Net Value</label></div>
    <div class="col-md-9">
    <input type="number" name="amount" class="form-control" value="{{ optional($asset)->amount }}">
    </div>
  </div>

</form>
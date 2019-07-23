@php( $type = \App\Models\DocType::find($typeid) )
@php( $parid = ($type ? $type->par : $typeid) )
@php( $maxnum = ($parid ? \App\Models\DocType::where('par', $parid)->max('num') : 0) )
<form id="doctype" method="POST" action="doctype/edit">
  {!! csrf_field() !!}
  <label>ID</label>
  <input type="text" class="form-control" name="typeid"  value="{{ $typeid }}">
  <label>Parent</label>
  <input type="text" class="form-control"  name="parid" value="{{ $parid }}">
  <div class="row">
    <div class="col">
      <label>Number</label>
      <input type="number" class="form-control"  name="num" value="{{ $type->num ?? $maxnum }}">
    </div>
    <div class="col">
      <label>Code*</label>
      <input type="text" class="form-control"  name="code" value="{{ $type->code ?? '' }}" required>
    </div>
  </div>
  <label>Name*</label>
  <input type="text" class="form-control" name="name" value="{{ $type->name ?? '' }}" required>
  <div class="row">
    <div class="col">
      <label>Doc Group</label>
      <input type="text" class="form-control" name="tname" value="{{ $type->tname ?? '' }}" placeholder="Leave blank for Team Code">
      </div>
    <div class="col">
      <label>Team Limit</label>
      <input type="text" class="form-control" name="des" value="{{ $type->des ?? '' }}">
      </div>
  </div>
</form>
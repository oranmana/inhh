<form>
  @can('isMaster')
  <label for="code" >ID</label>
  <input type="text" class="form-control" name="par" value="{{ $org->id }}">
  @endcan

  <label for="code" >Code</label>
  <input type="text" class="form-control" name="code" value="{{ $org->code }}" required>
  
  <label for="name" >Name</label>
  <input type="text" class="form-control" name="name" value="{{ $org->fullname }}"required>
  
  <label for="tname">Name In Thai (ชื่อเต็ม)</label>
  <input type="text" class="form-control" name="tname" value="{{ $org->tname }}" required>
  
  <label for="ref">Leavel</label>
  <select class="form-control" name="ref" required>
    <option value=0>-Select-</option>
    <option value=2{!! $org->ref==2 ? " SELECTED" : '' !!}>Department</option>
    <option value=3{!! $org->ref==3 ? " SELECTED" : '' !!}>Team</option>
    <option value=4{!! $org->ref==4 ? " SELECTED" : '' !!}>Section</option>
    <option value=5{!! $org->ref==5 ? " SELECTED" : '' !!}>Unit</option>
  </select>
  <div class="error text-danger" id="error_ref"></div>

  <label for="erp">Cost Center</label>
  <input type="text" class="form-control" name="erp" value="{{ $org->erp }}" required>

</form>
<div class="text-sm text-blue">* This section is not editable.</div>

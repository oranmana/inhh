<form id="renameorg" method="POST" action="{{ url('/org') }}/rename">
  {{ csrf_field() }}
  <input type="hidden" name="orgid" value="{{ $org->id }}">
  <label for="code">Unit Code</label>
  <input type="text" class="form-control"  name="code" placeholder="{{ $org->code }}" value="{{ $org->code }}" required>
  <label for="code">Name (in English)</label>
  <input type="text" class="form-control" name="name" placeholder="{{ $org->name }}" value="{{ $org->name }}" required>
  <label for="code">Name (in Thai) </label>
  <input type="text" class="form-control" name="tname" placeholder="{{ $org->tname }}" value="{{ $org->tname }}" required>
  <select class="form-control" name="ref" disabled>
    <option value=0>-Select-</option>
    <option value=2{!! $org->ref == 2 ? " SELECTED" : '' !!}>Department</option>
    <option value=3{!! $org->ref == 3 ? " SELECTED" : '' !!}>Team</option>
    <option value=4{!! $org->ref == 4 ? " SELECTED" : '' !!}>Section</option>
    <option value=5{!! $org->ref == 5 ? " SELECTED" : '' !!}>Unit</option>
  </select>
</form>

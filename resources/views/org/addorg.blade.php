<!-- <form id="addorg" method="POST" action="{{ url('/org') }}/addorg"> -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form id="addorg">
  {{ csrf_field() }}
  <input type="hidden" name="parid" value="{{ $parid }}">
  <label for="code" >Code*</label>
  <input type="text" class="form-control" name="code" placeholder="Abbreviation Code of Unit" value="{{ old('code') }}" required>
  <div class="error text-danger" id="error_code"></div>

  <label for="name" >Name*</label>
  <input type="text" class="form-control" name="name" placeholder="New Name (English)" value="{{ old('name') }}"required>
  <div class="error text-danger" id="error_name"></div>

  <label for="tname">Name (In Thai)</label>
  <input type="text" class="form-control" name="tname" placeholder="ชื่อ" value="{{ old('tname') }}" required>
  <div class="error text-danger" id="error_tname"></div>

  <!-- <input type="text" name="state" placeholder="Unit / Section / Team / Department" required> -->
  <label for="ref">Level</label>
  <select class="form-control" name="ref" required>
    <option value=0>-Select-</option>
    <option value=2{!! old('ref')==2 ? " SELECTED" : '' !!}>Department</option>
    <option value=3{!! old('ref')==3 ? " SELECTED" : '' !!}>Team</option>
    <option value=4{!! old('ref')==4 ? " SELECTED" : '' !!}>Section</option>
    <option value=5{!! old('ref')==5 ? " SELECTED" : '' !!}>Unit</option>
  </select>
  <div class="error text-danger" id="error_ref"></div>

  <label for="erp">Cost Center</label>
  <input type="text" class="form-control" name="erp" placeholder="Cost Center" required>
  <div class="error text-danger" id="error_erp"></div>

</form>
<div class="text-sm text-blue">* All Fields are required</div>

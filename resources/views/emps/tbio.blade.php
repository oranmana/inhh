<form id="form-bio">
  <table class="table table-sm" style=margin-bottom:0;>
    <tr>
      <td>
        <label for="id">Data ID</label>
      </td>
      <td>
        <input type="text" class="form-control" id="id" placeholder="Record ID" value="{{ $emp->id }}" readonly>
      </td>
      <td>
        <label for="type">Emp Code</label>
      </td>
      <td>
        <input type="text" class="form-control" id="id" placeholder="Employee code" value="{{ $emp->empcode }}" readonly>
      </td>
      <td>
        <label for="type">Tax Code</label>
      </td>
      <td>
        <input type="text" class="form-control"  placeholder="Tax Code" value="{{ $dir->code }}" readonly>
      </td>
    </tr>
    <tr>
      <td>
          <label for="sex">Gender</label>
      </td>
      <td>
          <select id=sex class="form-control">
            <option value=0 {!! $dir->sex==0?" selected":"" !!}>Mr.</option>
            <option value=1 {!! $dir->sex==1?" selected":"" !!}>Ms.</option>
            <option value=2 {!! $dir->sex==2?" selected":"" !!}>Mrs.</option>
          </select>
      </td>
      <td>
        <label for="name">Full Name</label>
      </td>
      <td colspan=3>
        <input type="text" class="form-control" id="name" placeholder="Enter Full Name" value="{{ $emp->name }}">
      <td>
    </tr>
    <tr>
      <td>
        <label for="name">Nick Name</label>
      </td>
      <td>
        <input type="text" class="form-control" id="nm" placeholder="Enter Nick Name" value="{{ $emp->nm }}">
      </td>
      <td>
        <label for="name">ชื่อเต็ม</label>
      </td>
      <td colspan=3>
        <input type="text" class="form-control" id="thname" placeholder="Enter Full Name in Thai" value="{{ $emp->thname }}">
      </td>
    </tr>
    <tr>
      <td>
        <label for="name">Birth Date</label>
      </td>
      <td>
        <input type="text" class="form-control" id="bdate" placeholder="Enter Nick Name" value="{{ edate($dir->bdate) }}">
      </td>
      <td>
        <label for="name">Age</label>
      </td>
      <td>
        <input type="text" class="form-control" value="{{ age($dir->bdate) }}" readonly>
      </td>
      <td>
        <label for="name">Birth Place</label>
      </td>
      <td>
        <input type="text" class="form-control" id="bplace" placeholder="Enter Birth Place/Province" value="{{ $emp->bplace }}">
      </td>
    </tr>
    <tr>
      <td rowspan=3>
        <label for="name">Home Address</label>
      </td>
      <td colspan=3  rowspan=3>
        <textarea class="form-control" id="diraddrss" placeholder="Home Address" rows=5>{{ $dir->adderss }}</textarea>
      </td>
      <td>
        <label for="name">Blood Group</label>
      </td>
      <td>
        <input type="text" class="form-control" id="blood" placeholder="Blood Group" value="{{ $emp->blood }}">
      </td>
    </tr>
    <tr>
      <td>
        <label for="name">Height</label>
      </td>
      <td>
        <input type="text" class="form-control" id="height" placeholder="Height (Cm)" value="{{ $emp->height }}">
      </td>
    </tr>
    <tr>
      <td>
        <label for="name">Weight</label>
      </td>
      <td>
        <input type="text" class="form-control" id="weight" placeholder="Weight (Kg)" value="{{ $emp->weight }}">
      </td>
    </tr>
    <tr>
      <td rowspan=3>
        <label for="name">Resident Address</label>
      </td>
      <td colspan=3  rowspan=3>
        <textarea class="form-control" id="empaddrss" placeholder="Resident Address" rows=5>{{ $emp->adderss }}</textarea>
      </td>
      <td>
        <label for="name">Type</label>
      </td>
      <td>
        <input type="text" class="form-control" id="house" placeholder="House Rental" value="{{ $emp->house }}">
      </td>
    </tr>
    <tr>
      <td>
        <label for="name">Vehicle</label>
      </td>
      <td>
        <input type="text" class="form-control" id="vehicle" placeholder="Vehicle" value="{{ $emp->vehicle }}">
      </td>
    </tr>
    <tr>
      <td>
        <label for="name">Home Tel.</label>
      </td>
      <td>
        <input type="text" class="form-control" id="weight" placeholder="Telephone" value="{{ $emp->tel }}">
      </td>
    </tr>
    <tr>
      <td>
        <label for="name">Religious</label>
      </td>
      <td>
        <select class="form-control" id="relg" value="{{ $emp->relg }}">
        @foreach ($relgs as $relg)
          <option value="{{ $relg->id }}"{!! ($relg->id == $emp->relg ? " selected" : "") !!}>{{ $relg->name }}</option>
        @endforeach
        </select>
      </td>
      <td>
        <label for="name">Military</label>
      </td>
      <td>
        <input type="text" class="form-control" id="military" placeholder="Military" value="{{ $emp->military }}">
      </td>
      <td>
        <label for="name">Mobile</label>
      </td>
      <td>
        <input type="text" class="form-control" id="mobile" placeholder="Mobile Number" value="{{ $emp->mobile }}">
      </td>
    </tr>
  </table>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

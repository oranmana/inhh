<form id="addjobtitle" method="POST" action="{{ url('/job/add') }}">
  {{ csrf_field() }}
  <input type="hidden" name="parid" value="{{ $parid }}">

  <label for="code" >Code</label>
  <input type="text" class="form-control" name="code" >

  <label for="name" >Name</label>
  <input type="text" class="form-control" name="name" required>
  <div class="error text-danger" id="error_code"></div>

  <label for="tname">Name In Thai (ชื่อเต็ม)</label>
  <input type="text" class="form-control" name="tname" required>
  <div class="error text-danger" id="error_tname"></div>

  <label for="cat">For Position Class</label>
  <select class="form-control" name="cat" required>
    <option value="0">-Position Class-</option>
  @php( $positions = \App\Models\EmpPosition::on()->get() )
  @foreach($positions as $post)
    <option value="{{ $post->id }}">{{ $post->name }}</option>
  @endforeach
  </select>
  <div class="error text-danger" id="error_cat"></div>

  <label for="erp">Cost Center</label>
  <input type="text" class="form-control" name="erp" required>
  <div class="error text-danger" id="error_erp"></div>

  <div class="bg-primary">PP Control</div>
  <!-- <label for="sub">T/O Number</label>
  <input type="number" class="form-control" name="sub" required>
  <div class="error text-danger" id="error_sub"></div> -->

  <div class="row">
    <label for="sub" class="col">T/O Number</label>
    <label for="type" class="col">Job Allowance</label>
  </div>
  <div class="row">
    <input type="number" class="form-control col ml-4" name="sub" required>
    <input type="number" class="form-control col mr-4" name="type" placeholder="per month">
  </div>
  <div class="row">
    <div class="error text-danger col" id="error_sub"></div>
    <div class="error text-danger col" id="error_type"></div>
  </div>

  <div class="bg-primary">Basic Requirement</div>
  <div class="row">
    <label for="gl" class="col">Min Age (Yr)</label>
    <label for="sfx" class="col">Max Age (Yr)</label>
  </div>
  <div class="row">
    <input type="number" class="form-control col ml-4" name="gl" required>
    <input type="number" class="form-control col mr-4" name="sfx" required>
  </div>
  <div class="row">
    <div class="error text-danger col" id="error_gl"></div>
    <div class="error text-danger col" id="error_sfx"></div>
  </div>

  <label for="pj">Min. Education Level</label>
  <select class="form-control" name="pj">
    <option value="0">-Education Level-</option>
  @php( $educations = \App\Models\Education::all() )
  @foreach($educations as $edu)
    <option value="{{ $edu->id }}">{{ $edu->name }}</option>
  @endforeach
  </select>
  <div class="error text-danger" id="error_pj"></div>

  <label for="dir">Average Salary</label>
  <input type="number" class="form-control" name="dir" required>
  <div class="error text-danger" id="error_dir"></div>

</form>
<div class="text-sm text-blue">* All fields are required input.</div>

</form>

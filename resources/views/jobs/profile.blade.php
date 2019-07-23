<form>
  @can('isMaster')
  <label for="id" >ID</label>
  <input type="text" class="form-control" name="id" value="{{ $job->id }}" readonly>
  @endcan

  <label for="code" >Job Code</label>
  <input type="text" class="form-control" name="code" value="{{ $job->code }}" readonly>
  
  <label for="name" >Job Name</label>
  <input type="text" class="form-control" name="name" value="{{ $job->name }}" readonly>
  
  <label for="tname">Job Thai Name (ชื่อเต็ม)</label>
  <input type="text" class="form-control" name="tname" value="{{ $job->tname }}" readonly>

  <label for="erp">Cost Center</label>
  <input type="text" class="form-control" name="erp" value="{{ $job->erp }}" readonly>
  <div class="bg-primary">PP Control</div>
  <label for="sub">T/O Number</label>
  <input type="number" class="form-control" name="sub" value="{{ $job->sub }}" 
  @can('isHRTM')
  @else
    readonly 
  @endcan
  >
  <label for="type">Job Allowance</label>
  <input type="number" class="form-control" name="type" value="{{ $job->allowance }}" readonly>
  <label for="cat">For Employee Position</label>
  <select class="form-control" name="cat">
  @php( $positions = \App\Models\EmpPosition::on()->get() )
  @foreach($positions as $post)
    <option value="{{ $post->id }}"{!! $job->cat == $post->id ? " SELECTED" : "" !!}>{{ $post->name }}</option>
  @endforeach
  </select>

  <div class="bg-primary">Basic Requirement</div>
  <div class="row">
    <label for="gl" class="col">Min Age (Yr)</label>
    <label for="sfx" class="col">Max Age (Yr)</label>
  </div>
  <div class="row">
    <input type="number" class="form-control col ml-4" name="gl" value="{{ $job->gl ?? 18 }}" required>
    <input type="number" class="form-control col mr-4" name="sfx" value="{{ $job->sfx ?? 30 }}" required>
  </div>
  <label for="pj">Min. Education Level</label>
  <!-- <input type="number" class="form-control" name="pj" value="{{ $job->pj }}" required> -->
  <select class="form-control" name="pj">
  @php( $educations = \App\Models\Education::all() )
  @foreach($educations as $edu)
    <option value="{{ $edu->id }}"{!! $job->pj == $edu->id ? " SELECTED" : "" !!}>{{ $edu->name }}</option>
  @endforeach
  </select>
  <label for="dir">Average Salary</label>
  <input type="number" class="form-control" name="dir" value="{{ $job->dir }}" required>

</form>
<div class="text-sm text-blue">* This section is not editable.</div>

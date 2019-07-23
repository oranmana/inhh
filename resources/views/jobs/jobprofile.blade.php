@extends('layouts.app')

@section('content')
@php( $job = \App\Models\jobtitle::find($jobid) )
@php( $emps = ($jobid ? \App\Models\Emp::Ofjob($jobid)
        ->with(['promotion','pos'=>function($q) {
          $q->selectRaw('*, num as positionnum') 
            ->orderBy('positionnum');
          }])->get()->sortBy('positionnum') : 0)
)
<div class="container">
  <div class="h3">
    <b><a id="profile" data-id="{{ $job->id }}">Job Title : {{ $job->name  }}</a></b>
  </div>
  
  <ul class="nav nav-tabs" id="jobtab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#div-profile" role="tab" aria-controls="div-profile" aria-selected="true">Job Profile</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="jd-tab" data-toggle="tab" href="#div-jd" role="tab" aria-controls="div-jd" aria-selected="false">Job Description</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="emp-tab" data-toggle="tab" href="#div-emplist" role="tab" aria-controls="div-emplist" aria-selected="false">Employees</a>
    </li>
  </ul>

  <div class="tab-content" id="jobcontent">
    <div class="tab-pane fade in active" id="div-profile" role="tabpanel" aria-labelledby="profile-tab">
      <form id="jobprofile">
        <div class="form-row">
          @can('isMaster')
          <div class="form-group col-md-4">
            <label for="id" >ID</label>
            <input type="text" class="form-control" name="id" value="{{ $job->id }}" readonly>
          </div>
          @endcan
          <div class="form-group col-md-4">
            <label for="code">Job Code</label>
            <input type="text" class="form-control" name="code" value="{{ $job->code }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label for="erp">Cost Center</label>
            <input type="text" class="form-control" name="erp" value="{{ $job->erp }}" readonly>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="name">Job Name</label>
            <input type="text" class="form-control" name="name" value="{{ $job->name }}" readonly>
          </div>
          <div class="form-group col-md-6">
            <label for="tname">Job Thai Name (ชื่อเต็ม)</label>
            <input type="text" class="form-control" name="tname" value="{{ $job->tname }}" readonly>
          </div>
        </div>

        <div class="bg-primary  pt-2 pb-2 pl-4 ml-0 mr-0">PP Control</div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="sub">T/O Number</label>
            <input type="number" class="form-control" name="sub" value="{{ $job->sub }}" 
              @can('isHRTM')
              @else
                readonly 
              @endcan
            >
          </div>
          <div class="form-group col-md-4">
            <label for="type">Job Allowance</label>
            <input type="number" class="form-control" name="type" value="{{ $job->allowance }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label for="cat">For Position</label>
            <select class="form-control" name="cat">
              @php( $positions = \App\Models\EmpPosition::on()->get() )
              @foreach($positions as $post)
              <option value="{{ $post->id }}"{!! $job->cat == $post->id ? " SELECTED" : "" !!}>{{ $post->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="bg-primary pt-2 pb-2 pl-4">Basic Requirement</div>

        <div class="form-row">
          <div class="form-group col-md-2">
            <label for="gl">Min Age (Yr)</label>
            <input type="number" class="form-control" name="gl" value="{{ $job->gl ?? 18 }}" required>
          </div>
          <div class="form-group col-md-2">
            <label for="sfx">Max Age (Yr)</label>
            <input type="number" class="form-control" name="sfx" value="{{ $job->sfx ?? 30 }}" required>
          </div>
          <div class="form-group col-md-5">
            <label for="pj">Min. Education</label>
            <select class="form-control" name="pj">
              @php( $educations = \App\Models\Education::all() )
              @foreach($educations as $edu)
              <option value="{{ $edu->id }}"{!! $job->pj == $edu->id ? " SELECTED" : "" !!}>{{ $edu->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="dir">Average Salary</label>
            <input type="number" class="form-control" name="dir" value="{{ $job->dir }}" required>
          </div>
        </div>
      </form>
    </div>

    <div class="tab-pane fade" id="div-jd" role="tabpanel" aria-labelledby="jd-tab">
      <span class='pt-4'>..Under Construction</span>
    </div>

    <div class="tab-pane fade" id="div-emplist" role="tabpanel" aria-labelledby="emp-tab">
      <div class="row col">
        <div class="h4 text-primary col">List of Engaged Employees</div>
        <div class="text-right col">
          <input type="radio" name="cat" value="1" checked onclick="$('.onjob, .offjob').toggle();"> Active
          <input type="radio" name="cat" value="0" onclick="$('.onjob, .offjob').toggle();"> Inactive
        </div>
      </div>
      <table class="table table-striped table-hover table-sm">
        <thead>
          <tr class="bg-primary">
            <th>No.</th>
            <th>Position</th>
            <th>Employee</th>
            <th>Employed</th>
            <th>Duration</th>
            <th>Promoted</th>
            <th>Active</th>
          </tr>
        </thead>
        <tbody>
        @if( $emps->count() )
          @php($ln=1)
          @foreach( $emps as $emp )
            @php( $promotion = $emp->promotion->first->toJson() )
            @php( $nopromoted = $promotion->indate == $emp->indate )
          <tr class="{{ $emp->qcode == 0 ? 'onjob' : 'offjob unseen' }}">
            <td class="text-right">{!! $ln++ !!}. </td>
            <td>{{ $emp->pos->code }}</td>
            <td><a onclick="viewempjob({{ $emp->id }});" data-toggle="modal" data-target="#ModalLong">{{ $emp->name }}<a>
              @if( $emp->qcode != 0 )
                <span class="badge badge-primary ml-3">Off</span>
              @endif
            </td>
            <td>{{ edate($emp->indate) }}</td>
            <td>{{ age($emp->indate)  }}</td>
            <td>{{ $nopromoted ? '-' : edate($promotion->indate) }}</td>
            <td>{{ $nopromoted ? '-' : age($promotion->indate)  }}</td>
          </tr>
          @endforeach
        @else
          <tr><td colspan=5 class="text-center"><i>-Vacant-</i></td></tr>
        @endif
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection

@section('script')
  <script>
    $(document).ready(function() {
      $('#profile').on('click', function() {
        var url = "{{ url('jobs') }}";
        window.location = url;
      });
    });
  </script>
@endsection
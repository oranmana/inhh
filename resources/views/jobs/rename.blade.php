@php($job = \App\Models\jobtitle::find($jobid))
<form id="renamejob" method="POST" action="{{ url('/job/rename') }}">
  {{ csrf_field() }}
  <input type="hidden" name="jobid" value="{{ $job->id }}">
  <label for="code">Job Code</label>
  <input type="text" class="form-control"  name="code" placeholder="{{ $job->code }}" value="{{ $job->code }}" required>
  <label for="code">Name (in English)</label>
  <input type="text" class="form-control" name="name" placeholder="{{ $job->name }}" value="{{ $job->name }}" required>
  <label for="code">Name (in Thai) </label>
  <input type="text" class="form-control" name="tname" placeholder="{{ $job->tname }}" value="{{ $job->tname }}" required>
</form>

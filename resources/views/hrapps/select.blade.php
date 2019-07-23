@php ($dir    = $app->dir)
@php ($job    = $app->hrrq->job)
@php ($pos    = $job->Position)
@php ($dfcls  = $pos->dfcls)
@php ($wf     = $pos->sub > 10 && $pos->sub < 20)
<div class="container">
<form method="POST" action="{{ url('app/selected') }}">
  {{ csrf_field() }}
  <input type=hidden name="appid" value="{{ $app->id }}">
  <table class="table table-sm">
    <tr scope="row">
      <td scope="col">Applicant</td>
      <td scope="col" style="width:50%"><input type=text class=form-control value="{{ $dir->fullname }}"></td>
      <td scope="col" class="bg-info" style="width:30%" align=center>Allowance</td>
    </tr>
    <tr scope="row">
      <td>Job Title</td>
      <td><input type=text name='jobid' class=form-control value="{{ $job->name }}"></td>
      <td><input type=number name='jobamt' class=form-control value="{{ $job->allowance }}"></td>
    </tr>
    <tr scope="row">
      <td>Class</td>
      <td><input type=text name='cls' class=form-control value="{{ $dfcls->name }}"></td>
      <td><input type=number name='clsamt' class=form-control value="{!! $dfcls->Allowance !!}"></td>
    </tr>
    <tr scope="row">
      <td>Position</td>
      <td>
        <select name="posid" class="form-control">
        @foreach($epos as $ps)
          <option value="{{ $ps->id }}"{!! ($ps->id == $pos->id ? ' SELECTED' : '') !!}>{!! $ps->code . ' : ' . $ps->name !!}</option>
        @endforeach
        </select>
      </td>
      <td><input type=number name='posamt' class=form-control value="{!! $pos->allowance($dfcls->code,0) !!}"></td>
    </tr>
    <tr scope="row">
      <td>Basic Salary</td>
      <td><input type=number name='amt' class=form-control value="{{ $app->amt }}"></td>
      <td><input type=number name='amtpost' class=form-control value="" placeholder="After Probation"></td>
    </tr>
    <tr scope="row">
      <td>Commenced On</td>
      <td><input type=date name="indate" class=form-control value="{{ $app->indate }}"></td>
      <td>
        <button role="submit" class="btn btn-primary">Confirm</button>
        <span class="btn btn-danger float-right" data-toggle="modal" data-target="#ModalLong">Cancel</span>
      </td>
    </tr>
  </table>
  
</form>
</div>
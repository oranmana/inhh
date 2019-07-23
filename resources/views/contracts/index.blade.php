@extends('layouts.app')


@section('content')
<div class="container">
  <form method="POST" action='/hrcons' class='form-group form-inline h2' id="headmenu"><div style='margin-right:30px'>HR Contract </div>
    {{ csrf_field() }}
    <select name="yr" class='form-control' onchange="$('#headmenu').submit();">
      <option value="{{ date('Y') }}">{{ date('Y') }}</option>
      @foreach($yrs->where('yr','<',date('Y')) as $y) 
      <option value="{{ $y->yr }}"{!! ($y->yr == $yr ? ' SELECTED' : '') !!}>{{ $y->yr}}</option>
      @endforeach
    </select> 
    <select name="type" class="form-control" onchange="$('#headmenu').submit();">
      <option value="0"{!! $type == 0 ? ' selected' : ''  !!}>Employment Contract</option>
      <option value="1"{!! $type == 1 ? ' selected' : '' !!}>Probation Pass</option>
    </select>
  </form>
  <table class="table table-striped table-hover table-sm">
    <thead>
      <tr>
        <th>No.</th>
        <th>App-Con</th>
        <th>Date</th>
        <th>Name</th>
        <th>Age</th>
        <th>Job Title</th>
        <th>Class</th>
        <th>In Date</th>
        <th>Ref</th>
      </tr>
    </thead>
    <tbody>
      @php($ln=1)
      @foreach($cons as $con)
        @php( $dir = $con->app ? $con->app->dir : $con->emp->dir )
      <tr >
        <td>{{ $ln++}} ) </td>
        <td title="{!! ($con->app?$con->app->rqid:0) !!}-{{ $con->id }}" align="center">{{ ($con->doc ? $con->doc->doccode : '-n/a-') }}</td>
        <td>{{ date('d-M-Y', strtotime($con->CREATED_AT)) }}</td>
        <td><a href="/hrcon/{{ $con->id }}" title="{{ $con->dirid }}-{{ $con->empid }}">
          {!! ($con->empid ? $con->emp->name : $dir->name) !!}</a></td>
        <td>{{ $dir->age }}</td>
        <td>{!! ($con->app?$con->app->hrrq->job->name:'-n/a-') !!}</td>
        <td>{{ $con->cls }}</td>
        <td title="{!! ( $con->todate > '' ? date('d-M-Y', strtotime($con->todate)) : '') !!}">
          {{ date('d-M-Y', strtotime($con->indate)) }}</td>
        <td class="small text-center{!! (isset($con->emp->qcode) &&  $con->emp->qcode> 0 ? ' bg-danger' : '') !!}">
          {{ ($con->probation ? $con->probation : 
          date('d-M-Y', strtotime(isset($con->emp->qcode) &&  $con->emp->qcode > 0 ? $con->emp->qdate : $con->probdate))
           ) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>@endsection
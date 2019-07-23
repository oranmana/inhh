@extends('layouts.app')

@section('content')
@php( $job = \App\Models\jobtitle::find(\App\Models\jobtitle::TOPJOB) )
<div class="container">
  <div class="h3">Job Title Table</div>
    <div class="tbl-header">
      <table class="table table-sm">
        <thead>
          <tr>
            <th>Under</th>
            <th>Code</th>
            <th>Job Title</th>
            <th class="text-center">Level</th>
            <th class="text-center">T/O</th>
            <th class="text-center">Occupied</th>
            <th class="text-center">Balance</th>
          </tr>
        <!-- </thead>
      </table>
    </div>
    <div class="tbl-content">
      <table class="table table-sm">
        <tbody> -->
        @include('jobs.joblist', ['jobpar'=>$job,'level'=>0] )
        </tbody>
      </table>
    </div>
</div>
@endsection

@section('script')
<script>
  $(document).ready(function() {
    //setthwidth();
    $('.job').on('click', function() {
      window.location = "{{ url('jobs/view') }}" + "/" + $(this).attr('data-id');
    });

//    setthhead();
  });
</script>
@endsection

@extends('layouts.app')
@section('content')
<div class="container">
  <h2><a href="{{ URL::previous() }}">Employee Profile - {{ $dir->fullname }} </a></h2>
  <nav>
    <div class="nav nav-tabs" id="maintab" role="tablist">
    @php( $tabs = array('Employment','Promotion','Attendance','Benefit','Biological','Relatives','Education','Training','Payroll'))
    @php( $tabs = array('Biological','Relatives','Education','Employment','Promotion','Benefit','Attendance','Payroll','Training'))
    @foreach($tabs as $i=>$tab)
      <a class="nav-link{!! ($i ? '' : ' active') !!}" id="{{ $tab }}-tab" data-toggle="tab" href="#{{ $tab }}" href="#tab{{ ($i+1) }}" role="tab" aria-controls="{{ $tab }}" aria-selected="{!! ($i ? 'false' : 'true') !!}">{{ $tab }}</a>
    @endforeach
    </div>
  </nav>
  <div class="tab-content" id="tabcontent">
    <div id="Employment"  class="tab-pane fade" role="tabpanel">
      @include('emps.temploy')
    </div>  
    <div id="Promotion"  class="tab-pane fade" role="tabpanel">
      @include('emps.tpromotion')
    </div>  
    <div id="Benefit"  class="tab-pane fade" role="tabpanel">
      @include('emps.tpayrate')
    </div>  
    <div id="Relatives" class="tab-pane fade" role="tabpanel">
      @include('emps.trel')
    </div>
    <div id="Biological" class="tab-pane fade-in active" role="tabpanel">
      @include('emps.tbio')
    </div>
    <div id="Payroll" class="tab-pane fade" role="tabpanel">
      @include('emps.tpayroll')
    </div>
    <div id="Education" class="tab-pane fade" role="tabpanel">
      @include('emps.teducation')
    </div>
    <div id="Attendance" class="tab-pane fade" role="tabpanel">
      @php($years = $emp->AttendanceYears)
      <select class="form-control" id="selectyear">
        @foreach($years as $yr)
          @if(! isset($year) )
            @php($year=$yr->yr)
          @endif
          <option value="{{ $yr->yr }}">{{ $yr->yr}} Attendance</option>
        @endforeach
      </select>
      <div id="attendancetable">
      </div>
    </div>
    <div id="Training" class="tab-pane fade" role="tabpanel">
      @include('emps.ttraining')
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  $(document).ready(function(){
    
    // $.get('rel/{{ $emp->id }}',function() {
    // }).done(function(data) {
    //     $('#tab3').empty().html(data);
    // });
    // Make Attendance Table for the Lastest Year 
    $('#selectyear').on('change',function() {
      getAttendance();
    });
    getAttendance();

  });

  function getAttendance() {
    $.ajax({
      method : "GET",
      url : "{{ url('empattendance/'.$emp->id ) }}" + "/" + $('#selectyear').val()
    }).done(function(data) {
      $('#attendancetable').empty().html(data);
    });
  }
  function getpay(yr) {
    $.ajax({
      method : "GET",
      url : "{{ url('emppays/'.$emp->id ) }}" + (yr ? "/" + yr : '')
    }).done(function(data) {
      $('#Payroll').empty().html(data);
    });
  }

</script>
@endsection
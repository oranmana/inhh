@php( $cal = ($calid ? \App\Models\Calendar::find($calid) : 0) )
@php( $defaultdate = ($cal ? $cal->cldate : date('Y-m-d') ) )
<form id="addholiday" method="post" action="{{ url('calendar/addholiday') . '/' . $yr }}">
  {{ csrf_field() }}
  <input type="hidden" name="calid" value="{{ $calid }}" > 
  <input type="hidden" id="mode" name="mode" value="{{ empty($cal) ? 1 : 9 }}" > 
  
  <label>Date :</label>
  <input type="text" id="caldatetxt" class="form-control datefield" value="{{ date('d-M-Y (D)', strtotime($defaultdate)) }}" > 
  <input type="date" id="caldate" name="caldate" class="form-control datefield" value="{{ date('Y-m-d', strtotime($defaultdate)) }}" required>
  
  <label>Description : </label>
  <input type="text" name="calmemo" class="form-control" value="{{ $cal->rem ?? '' }}" required>
  
</form>

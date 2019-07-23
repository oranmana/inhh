@extends('layouts.app')

@section('content')
@php( $emp = \App\Models\Emp::find($empid) )
<div class="container">
  <div class="row">
    <div class="h3 pr-3">My Leave</div>
    <select id="yr" class="h3">
      @foreach($yrs as $year)
      <option value="{{ $year }}"{!! $year==$yr ? " selected" : '' !!}>{{ $year }}</option>
      @endforeach
    </select>
  </div>
  <div class="h3">For : {{ $emp->fullname }}</div>
  <div class="row">
    <div class="col-4">
      <table>
        <tr>
          <th class="pl-2 pr-2">Leave Item</th>
          <th class="pl-2 pr-2" title="Limit for paid (Days)">Limit</th>
          <th class="pl-2 pr-2"title="Advance Notice (Days)">Advance</th>
          <th class="bg-info pl-2 pr-2">{{ $yr }}</th>
        </tr>
      @foreach($leaves as $lv)
        @if($lv->num == 1)
          <tr class="bg-primary">
            <td colspan=4 class="bold">{{ $lv->parent->name }}</td>
          </tr>
        @endif
        <tr>
          <td><a data-id="{{ $lv->id }}" class="lvtype">- {{ $lv->name }}</a></td>
          <td class="text-center">{{ fnum($lv->sub) }}</td>
          <td class="text-center">{{ fnum($lv->des ?? 0) }}</td>
          <td class="text-right bg-info">{{ fnum($lv->lvqty,2) }}</td>
        </tr>
      @endforeach
      </table>
    </div>
    <div id="subwin" class="col-8">
    @if($emp->IsActive)
      @include('myleave.leaveform')
    @endif
    </div>
  </div>
</div>
@endsection

@section('script')
<script>

$(document).ready(function() {

  $('#yr').css('border','none');
  $('#yr').on('change', function() {
    window.location = "{{ url('myleave') }}" + "/" + $(this).val() + "/" + {{ $empid }};
  });

  $('input[name=fromdate]').on('change', function() {
    $('input[name=todate]').val($(this).val());
    $('input[name=todate]').prev('.datetext').val($(this).prev('.datetext').val());
  });

  $('[name=leavetye]').on('change', function() {
    var reason = $(this).find('option:selected').attr('reason');
    $('[name=rs').val( reason.length );
    $('[name=reason').attr('placeholder', reason);
  });
  $('#leavetype').on('change', function() {
    var reason = $(this).find('option:selected').attr('reason');
    $('#reason').attr('placeholder', reason);
  });
  $('.lvtype').on('click', function() {
    listleave({{ $empid }},{{ $yr }},$(this).attr('data-id'));
  });
});

function listleave(empid, yr, type) {
  $.ajax({
    type : "GET",
    url : "{{ url('myleave/list') }}" + "/" + yr + "/" + empid + "/" + type
  }).done(function(data) {
    $('#subwin').empty().html(data);
  })
}
</script>
@endsection
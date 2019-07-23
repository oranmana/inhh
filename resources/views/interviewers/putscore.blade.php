@extends('layouts.app')

@section('content')

@php( $userempid = auth()->user()->empid )
@php( $uemp = \App\Models\Emp::Find($userempid))
<div class="container">
  <div>User : {{ $uemp->fullname }}</div>
  <div>Interviewer : {{ $app->emp->fullname }}</div>
  {{-- @if($userempid != $app->empw) --}}
  @if(false)
    <div>You are not authorized to access the process.</div>
  @else
    @if($app->accept > 0)
      <div>You have {!! strtolower($app->decisiontxt) !!} this applicant on {{ date('d-M-Y H:i', strtotime($app->UPDATED_AT)) }} ({{ $app->accept }})</div>
    @endif
    <table>
      <tr>
        <td colspan=2><hr></td>
      </tr>
      <tr>
        <td class="pr-5">For Job Title</td>
        <td>{{ $app->app->hrrq->job->name}} </td>
      </tr>
      <tr>
        <td>Required On</td>
        <td>{{ edate($app->app->hrrq->rqdate) }}</td>
      </tr>
      <tr>
        <td colspan=2><hr></td>
      </tr>
      <tr>
        <td class="pr-5">Applicant ID.</td>
        <td>{{ $app->app->hrrq->id}}-{{ $app->app->id}} </td>
      </tr>
      <tr>
        <td class="pr-5">Date</td>
        <td>{{ edate($app->app->wdate) }} </td>
      </tr>
      <tr>
        <td>Applicant</td>
        <td>{{ $app->app->dir->fullname}} </td>
      </tr>
      <tr>
        <td>Age</td>
        <td>{{$app->app->dir->age}}</td>
      </tr>
    </table> 
    @php($s = $app->sa)
    <table>
      <tr class="trhead bg-info">
        <th>No.</th>
        <th>Topic</th>
        <th class="rscore">Your Score</th>
        <th class="escore">Score</th>
      </tr>
      <tr>
        <td class="text-right">1.</td>
        <td>Personality</td>
        <td class="rscore text-right">{{ $app->s1 }}</td>
        <td class="escore text-right"><input type="number" class="sscore w-5" value="{{ $app->s1 }}"></td>
      </tr>
      <tr>
        <td class="text-right">2.</td>
        <td>Job Knowledge</td>
        <td class="rscore text-right">{{ $app->s2 }}</td>
        <td class="escore text-right"><input type="number" class="sscore w-5" value="{{ $app->s2 }}"></td>
      </tr>
      <tr>
        <td class="text-right">3.</td>
        <td>Working Attitude</td>
        <td class="rscore text-right">{{ $app->s3 }}</td>
        <td class="escore text-right"><input type="number" class="sscore w-5" value="{{ $app->s3 }}"></td>
      </tr>
      <tr>
        <td class="text-right">4.</td>
        <td class="pr-5">Useful Experience</td>
        <td class="rscore text-right">{{ $app->s4 }}</td>
        <td class="escore text-right"><input type="number" class="sscore w-5" value="{{ $app->s4 }}"></td>
      </tr>
      <tr class="bg-success">
        <td class="text-center" colspan=2 >TOTAL</td>
        <td class="text-right" id="sum">{{ $app->scores }}</td>
      </tr>
    </table>
    <div id="btnedit" class="btn btn-warning">Edit</div>
  @endif
</div> 

@endsection

@section('script')
<script>
  $(document).ready(function() {
    $('.escore').hide();

    $('.sscore').on('change', function() {
      var sum = 0;
      $('.sscore').each(function(){
          sum += parseInt(this.value);
      });
      $('#sum').text(sum);
    });

    $('#btnedit').on('click', function() {
      $('.rscore, .escore').toggle();
    });

  });
</script>
@endsection
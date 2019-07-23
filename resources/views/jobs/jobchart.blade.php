@php($jobs = $jobpar->children()->whereOff(0)->orderBy('num')->get() )
@php($level = (isset($level) ? $level+1 : 0))

<ul class="tree" level-id="{{$level}}">
@foreach( $jobs as $job )
  @php($childnum = $job->children()->On()->count())
  <li {!! $childnum ? " child=" . $childnum : "" !!}  >
    <span class="mark">{!! ($childnum > 0 ? "▾" : "▹") !!}</span> 
    <a class="job" draggable="true" data-id="{{ $job->id }}">
      {!! $job->name . ($job->des ? ' ' . $job->des : '') !!}
       ({!! fnum($job->emp->where('qcode',0)->count()).'/'. fnum($job->TOnum) !!})
    </a>
    @if($childnum > 0)
      @include("jobs.jobchart", ['jobpar'=>$job,'level'=>$level])
    @endif
  </li>
@endforeach
</ul>
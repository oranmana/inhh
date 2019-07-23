@php( $jobs = $jobpar->children()->whereOff(0)->orderBy('num')->get() )
@php( $level++ )

@foreach( $jobs as $job )
  @php( $childnum = $job->children()->On()->count() )
  @php( $occupied = $job->emp->where('qcode',0)->count() )
  @php( $balance = $job->TONum - $occupied )
  <tr class="tree" level-id="{{$level}}">
    <td>{{ $job->org->fullname ?? '' }}</td>
    <td>{{ $job->code ?? '' }}</td>
    <td {!! $childnum ? " child=" . $childnum : "" !!} >
      <span>{!! str_repeat('∙ ', $level-1) !!}</span>
      <a class="job" draggable="true" data-id="{{ $job->id }}">
        {!! $job->name . ($job->des ? ' ' . $job->des : '') !!}
      </a>
    </td>
    <td class="text-center"><span class="badge badge-primary">{{ $level }}</span></td>
    <td class="text-center">{{ $job->TONum }}</td>
    <td class="text-center">{!! fnum($occupied) !!}</td>
    <td class="text-center">{!! fnum($balance) !!}
  </tr>
  @if($childnum > 0 && $level < 10)
    @include("jobs.joblist", ['jobpar'=>$job,'level'=>$level])
  @endif

@endforeach

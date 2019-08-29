@php($orgs = $orgpar->children()->whereOff(0)->orderBy('num')->get() )
@php($level = (isset($level) ? $level+1 : 0))

@if($level==0)
<div class="h4"><a class="org toporg" data-id="{{ $toporg }}">Organization Chart</a></div>
@endif

<ul class="tree" level-id="{{$level}}">
@foreach( $orgs as $orgunit )
  @php($childnum = $orgunit->children()->On()->count() )
  @php($empnum = $orgunit->emps()->isActive()->count() )
  <li >
    <span class="mark" >{!! ($childnum > 0 ? "▾" : "▹") !!}</span> 
    <a class="org{{$childnum ? '' : ' nomember'}}" draggable="true" data-id="{{ $orgunit->id }}"  {!! $childnum ? " child=" . $childnum : "" !!} {!! $empnum ? " empnum='" . $empnum . "'" : '' !!} {!!$orgunit->tname ? " title='" . $orgunit->tname . "'" : ''!!}>{!! $orgunit->name . ($orgunit->des ? ' ' . $orgunit->des : '') . ($empnum ? ' ('. $empnum . ')' : '' )  !!}</a>
    @if($childnum > 0)
      @include("org.orgchart", ['orgpar'=>$orgunit,'level'=>$level])
    @endif
  </li>
@endforeach
</ul>

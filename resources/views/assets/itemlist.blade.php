@php($items = $itempar->children()->orderBy('num')->get() )
@php($level = (isset($level) ? $level+1 : 0))

@if($level==0)
<div class="h5">Asset Items</div>
@endif
<ul class="tree" level-id="{{$level}}">
@foreach( $items as $itm )
  @php($childnum = $itm->children->count())
  <li {!! $childnum ? " child=" . $childnum : "" !!} data-id="{{ $itm->id }}" >
    <span class="mark">{!! ($childnum > 0 ? "▾" : "▹") !!}</span> 
    <a>{{ $itm->name }}</a>
    @if($childnum > 0)
      @include("assets.itemlist", ['itempar'=>$itm,'level'=>$level])
    @endif
  </li>
@endforeach
</ul>
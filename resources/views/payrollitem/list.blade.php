@php( $lists = \App\Models\PayrollItem::On()->ofPar($parid)->get() )
@foreach($lists as $list)
  @php( $children = $list->child->count() )
  @php( $text =  str_repeat('>', $level) . ' ' .  $list->name . ( strlen($list->code) ? ' ('. $list->code . ')' : '' ))
  
  <a href="#" class="item ml-{{ $level*2 }}{{ $level==0 ? " h4 bg-primary" : ""}}" data-id="{{ $list->id }}">{!! $level < 2 ? "<strong>" . $text . "</strong>" : $text !!}</a><br>
  @if( $children > 0 )
    @php($nextlevel = $level+1)
    @include('payrollitem.list', ['parid'=>$list->id, 'level' => $nextlevel])
  @endif
@endforeach
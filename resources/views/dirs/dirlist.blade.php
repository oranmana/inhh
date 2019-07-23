@foreach($dirs as $dir)
  <tr class="row_dir" >
    <td>
      <a href="/dirs/{{ $dir->id }}">{{ strtoupper($dir->name) }} </a>
    </td>
    <td nowrap>
        @php( $nation = App\Models\Country::find($dir->nation == 0 ? 45 : $dir->nation) )
        <a>{!! ($dir->IsNotThai ? strtoupper($nation->name) : $dir->tname )!!} </a>
    </td>
    <td>
        <a>{{ $dir->tel }} </a>
    </td>
</tr>
@endforeach

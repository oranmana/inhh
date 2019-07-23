<div class="calendarmonth">
  @php ( $firstdate = $month->first() )
  @php ( $lastdate = $month->last() )
  <div class="caldendar bg-primary text-white"><b>{{ date('F, Y', $firstdate->TimeStr) }}</b></div>
  <table class="table table-sm text-center">
    <thead>
      <tr>
        <th class="bg-danger">Sun</th>
        <th>Mon</th>
        <th>Tue</th>
        <th>Wed</th>
        <th>Thu</th>
        <th>Fri</th>
        <th>Sat</th>
      </tr>
    </thead>
    <tbody>
    @for ($i=0; $i < $firstdate->DayNum; $i++)
      @if($i==0)
      <tr>
      @endif
      <td></td>
    @endfor
    @foreach($month as $day)
      @if(! $day->DayNum)
      <tr>
      @endif
      <td data-id="{{ $day->id }}">
        <div class="daydate{!! 
            (! $day->DayNum ? " text-white bg-secondary" : '') 
          . ($day->Daynum == 6 && ! $day->holiday==2 ? " text-info bg-light" : '')
          . ($day->holiday==2 ? " public bg-info\" title=\"" . $day->rem . "\"" : "\"") 
        !!}>{{ $day->day }}</div>
      </td>
      @if($day->DayNum == 6)
      </tr>
      @endif
    @endforeach
    @for ($i=6; $i > $lastdate->DayNum; $i--)
      <td></td>
    @endfor
    </tr>
    </tbody>
  </table>

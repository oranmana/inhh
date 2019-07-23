@extends('layouts.app')

@section('content')
@if(! isset($year) )
  @php( $year = date('Y') )
  @php( $TrainingYears = \App\Models\Training::whereRaw('year(ondate) > 2000')->where('state','<',9)
            ->selectRaw('year(ondate) as yr')
            ->groupBy('yr')->orderBy('yr','desc')->pluck('yr') )
  @php( $TrainingTypes = \App\Models\Common::Training()->get() )
@endif
@php( $trainings = \App\Models\Training::OfYear($year)->where(function($q) use ($type) {
    if ($type) {
      $q->OfCategory($type);
    }
  })->get()
)
  <div class="container">
    <form id="trainmenu">
      <div class="row col">
        <div class="form-inline col">
          <label for="tryear" class="h3 mb-4 mr-3">Training </label>
          <select id="tryear" name='yr' class="form-control mr-3">
            @foreach($TrainingYears as $yr) 
            <option value="{{ $yr->yr }}" {!! ($yr->yr == $year ? 'selected' : '') !!}>{{ $yr->yr }}</option>
            @endforeach
          </select>
          <select id="trtype" name='type' class="form-control">
            <option value=0>-All Training-</option>
            @foreach($TrainingTypes as $tp) 
            <option value="{{ $tp->id }}" 
              {!! ($tp->id == $type ? 'selected' : '') !!}>
              {{ $tp->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="float-right"><a id="addtrain" class="btn btn-primary text-white">Add Course</a></div>
      </div>
  </form>

  <table class="table table-sm table-striped table-hover">
    <thead>
      </thead>
        <tr>
          <th>Code</th>
          <th>Date</th>
          <th>Until</th>
          <th>Course</th>
          <th>Location</th>
        </tr>      
      <tbody>
      @foreach($trainings as $tr)
        <tr data-id = "{{ $tr->id }}">
          <td nowrap><a class="train">{{ $tr->code }}</a></td>
          <td nowrap>{{ edate($tr->ondate) }}</td>
          <td nowrap>{{ edate($tr->todate) }}</td>
          <td>{{ $tr->coursename }}</td>
          <td>{{ $tr->remark }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
@include('partials.modal')
@endsection

@section('script')
<script>
  $('#tryear, #trtype').on('change', function() {
   let url = "{{ url('/training')}}"+'/'+$('#tryear').val() 
      + ($('#trtype').val() ? '/' + $('#trtype').val() : '');
    location.href = url;
  });

  $('.train').on('click', function() {
    let trainid = $(this).closest('tr').attr('data-id');
    let url = "{{ url('/train')}}"+'/'+trainid;
    location.href = url;
  });
</script>
@endsection
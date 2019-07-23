@php( $train = \App\Models\Training::Find($trainid) )
@if($attnid)
<form id="trainins" method="POST" action="{{ url('train/attn/edit') }}">
  {{ csrf_field() }}
  <input type="text" name="trainid" value="{{ $trainid }}">
  <input type="text" name="attnid" value="{{ $attnid }}">
  <label>Name</label>
  <input type="text" id="attnname" class="form-control">
</form>
@else
  @php( $empstrained = \App\Models\Trainings::OfTrain($trainid)->pluck('emp_id') )
  @php($attns = \App\Models\Emp::OnDuring($train->ondate)->whereNotIn('id', $empstrained)->orderBy('thname')->get() )
  <form id="trainins" method="POST" action="{{ url('train/attn/add') }}">
    {{ csrf_field() }}
    <input type="hidden" name="trainid" value="{{ $trainid }}">
    <label class="h3">Select Attendee</label>
    <select id="empselect" class="col-5 form-control" name="emplist" size=10 multiple="true">
    @foreach ($attns as $attn)
      <option value="{{ $attn->id }}">{{ $attn->thabfullname }}{{ empty($attn->qdate) ? '' : '*' }} </option>
    @endforeach
    </select>
    <div id="selectcount"></div>
  </form>
@endif
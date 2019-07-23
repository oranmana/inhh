<select multiple class="form-control" id="interviewers" size={!! ($emps->count() > 10 ? 10 : $emps->count()) !!}>
@foreach ($emps as $emp)
  <option value="{{ $emp->id }}">
    {{ $emp->name }} [{{ $emp->curjob->code }}]
  </option>
@endforeach
</select>
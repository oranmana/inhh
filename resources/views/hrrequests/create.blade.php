<div class="col">
  <form method=post action="{{ url('hrrq/add') }}">
    {{ csrf_field() }}
    <label for="jobid">Job Title</label>
    <select name="jobid" id="jobid" class="form-control" required>
      <option value=0>-Select Job Title-</option>
      @foreach($jobs as $jb)
      <option value="{{ $jb->id }}">{{ $jb->name }} ({{$jb->vacantnums}})</option>
      @endforeach
    </select>
    <!-- <label for="docid">Input Document <small>(Unemploy / Re-organization / T/O Adjusted)</small></label>
    <input type="text" name="doccode" class="form-control"> -->

    <label for="jobid">Reference <small>(Unemploy / Re-organization / T/O Adjusted)</small></label>
    <select name="docid" id="docid" class="form-control" required>
      <option value=0>-Select Document-</option>
      @foreach($docs as $dc)
      <option value="{{ $dc->id }}" data-job="{{ $dc->ref }}">{{ $dc->doccode }} : {{$dc->name}} ({!! date('d-M-Y', strtotime($dc->indate)) !!})
        {!! ($dc->hrrq->count() ? ' ('.$dc->hrrq->count() . ')':'') !!}
      </option>
      @endforeach
    </select>

    <label for="rqdate">Required On</label>
    <input type="date" name="rqdate" class="form-control" value="{!! date('Y-m-d', strtotime('+1 month')) !!}" required>
    <br>
    <div class="row bg-info">Requirement</div>

    <label for="age">Age</label>
    <div class="row">
      <div class="col">
        <input type="text" id="minage" name="minage" class="form-control" value="{!! $job ? $job->MinAge : 24 !!}" required>
      </div>
      <div class="col">
        <input type="text" id="maxage" name="maxage" class="form-control" value="{!! $job ? $job->MaxAge : 40 !!}" required>
      </div>
    </div>
    <label for="age">Min.Education Level</label>
    <select id="eduid" name="eduid" class="form-control" required>
      <option value=0>-Select Education Level-</option>
      @foreach($edus as $ed)
      <option value="{{ $ed->id }}">{{ $ed->name }}</option>
      @endforeach
    </select>
    
    <label for="jobdes">Other Requirement</label>
    <textarea class="form-control" id="jobdes" name="jobdes">{{ isset($job) ? $job->des : '' }}</textarea>

    <label for="wage">Average Wage</label>
    <input type="text" id="wage" name="wage" class="form-control" value="{!! ($job ? $job->dir : 0) !!}">
    <button type="submit" class="btn btn-primary">Add Request</button>
  </form>
</div>

<script>
  $(document).ready(function () {
    $('#jobid').on('change', function() {
      $.ajax({
        url: "{{ url('/job') }}" + '/' + $(this).val() ,
        type: "GET",
        success: function(res) {
          $("#minage").val(res.minage);
          $("#maxage").val(res.maxage);
          $("#eduid").val(res.eduid);
          $("#jobdes").val(res.jobdes);
          $("#wage").val(res.wage);
          $doc = $("#docid option[data-job='" + $('#jobid').val() + "']").val();
          if ($doc) $('#docid').val( $doc );
        } // sucess
      }); // ajax
    }); 
  });    
</script>
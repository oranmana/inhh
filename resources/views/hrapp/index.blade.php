@extends('layouts.app')

@section('content')
  @php($rq = $app->hrrq)
  @php($dir = $app->dir)
<div class="container">
<!-- hrapp.index -->
<h2><a href="{{ url('hrapps/'.$rq->id) }}">Applicant Evaluation</a></h2>
  <div class="row">
    <div class="col-6">
      <!-- <form method="POST" id=hrApp class="form-group"> -->
        <table class="table table-sm">
          <tr>
            <td scope="col">
              <div class="droparea" target="emp">Photo</div>
            </td>
            <td scope="col">
              <table class="table table-sm">
                <tr>
                  <td scope="col">
                    <label for="app.no">Application</label>
                  </td>
                  <td scope="col">
                    <input type=text class="form-control" id="app.no" value="{!! $rq->id . '-' . $app->id .'/' . $dir->id !!}" readonly>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="job.name">Job Title</label>
                  </td>
                  <td>
                    <input type=text class="form-control" name="job.name" value="{{ $rq->job->name }}" readonly>
                  </td>
                </tr>
                @if($rq->rcid)
                <tr>
                  <td>
                    <label for="rq.rc.indate">Recruited On</label>
                  </td>
                  <td>
                    <input type=text class="form-control" name="job.name" value="{{ $rq->rc->indate ?? '' }}" readonly>
                  </td>
                </tr>
                @else
                <tr>
                  <td>
                    <label for="rq.rqdate">Required On</label>
                  </td>
                  <td>
                    <input type=date class="form-control" name="rq.rqdate" value="{{ $rq->rqdate }}" readonly>
                  </td>
                </tr>
                @endif
                <tr class="table-info">
                  <td>
                    <label for="app.wdate">Interview On</label>
                  </td>
                  <td>
                    <input type="datetime-local" class="form-control" name="app.wdate" value="{!! ($app && isdate($app->wdate) ? date('Y-m-d',strtotime($app->wdate)).'T'.date('H:i',strtotime($app->wdate)) : date('Y-m-d').'T10:00') !!}" readonly>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td scope="col">
              <label for="dir.code">Tax ID :</label>
            </td>
            <td scope="col">
              <input type=text class="form-control" id="dir.code" value="{{ $dir->code }}">
            </td>
          </tr>
          <tr>
            <td scope="col">
              <label for="dir.name">Name :</label>
            </td>
            <td scope="col">
              <div class="form-inline">
                <input type="text" class="form-control col-2" value="{{ $dir->gender }}">
                <input type="text" class="form-control col-10" name="dir.name" value="{{ $dir->name }}">
              </div>
            </td> 
          </tr>
          <tr>
            <td scope="col">
              <label for="age">Age :</label>
            </td>
            <td scope="col">
              <input type=text class="form-control" id="age" value="{{ $dir->age }}">
            </td>
          </tr>
          <tr>
            <td>
              <label for="age">Education</label>
            </td>
            <td>
              @php ($edu = $dir->EduLevel)
              <input type=text class="form-control" id="educate" value="{!! ($edu ? $edu->name : '') !!}">
            </td>
          </tr>
          </table>
      <!-- </form> -->
      <form method="post" action="{{ url('/app/afterinterview') }}">
        {{ csrf_field() }}
        <table class="table table-sm table-borderless">
          <tr>
            <td scope="col">
              <label for="appamt">Min.Salary</label>
            </td>
            <td scope="col">
              <input type="hidden" class="form-control" name="appid" value="{{ $app->id }}">
              <input type="number" class="form-control" id="appamt" name="appamt" value="{{ $app->amt }}">
            </td>
          </tr>
          <tr>
            <td>
              <label for="appindate">Available On</label>
            </td>
            <td>
              <input type="date" class="form-control" id="appindate" name="appindate" value="{{ $app->indate }}">
            </td>
          </tr>
          <tr>
            <td>
              <label for="apprem">Remark</label>
            </td>
            <td>
              <input type="text" class="form-control" id="apprem" name="apprem" value="{{ $app->rem }}">
            </td>
          </tr>
          <tr id="submitafter">
            <td>
            </td>
            <td>
              <button role="submit">Save</button>
            </td>
          </tr>
        </table>
      </form>
    </div>
    <div class="col">
      <div class="row" id="pg1">
        <div class="col bg-info">
          <span class="h3">Interviewers @if('can:isHR')(HR)@endif </span>
          @can('isHR')
          @if ($app->amt > 0)
            <span class="pull-right"><a id=addinterviewer data-id="{{ $rq->jobid }}" data-toggle="modal" data-target="#ModalLong">Add</a></span>
          @endif
          @endcan
        </div>
        @php ($itws = $app->interviewers )
        @if ($itws && $itws->count() )
        <table class="table">
          <tbody>
            @foreach($itws as $itw)
            <tr>
              <td><a href="#icard" class="icard" data-id="{{ $itw->id }}">{{ $itw->emp->name }}</a>
              <br>({{ $itw->emp->curjob->name }})</td>
              <td align=right>{{ $itw->sc }}</td>
              <td>{{ $itw->accepted }}</td>
              <td>{{ $itw->rem }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
        
      </div>    
      <form id="evappscores" class="form-group">
      {{ csrf_field() }}
      <table class="table table-sm" id="icard">
        <tr>
          <td width=25% align=center><span class="btn btn-info">Evaluator</span></td>
          <td><select class="form-control" id="evname" name="evname"> 
              <option value="0" data-value="{{$app->state}},{{$app->s1}},{{$app->s2}},{{$app->s3}},{{$app->s4}},{{$app->s5}}">-All-</option>
              @foreach($app->interviewers as $evs)
                <option value="{{$evs->id}}" data-value="{{$evs->accept}},{{$evs->s1}},{{$evs->s2}},{{$evs->s3}},{{$evs->s4}},{{$evs->s5}}"
                data-rem="{{ $evs->rem }}">{{ $evs->emp->name }}</option>
              @endforeach
            </select>
          </td>
        </tr>
      </table>
      <table class="table table-sm table-bordered">
        <thead>
          <th >No.</th>
          <th width=60%>Topic</th>
          <th width=25%>Score</th>
        </thead>
        <tbody>
          @php($ln=1)
            @for($k=1;$k<=4;$k++)
              @php($sc = 0)
          <tr class="text-right">
            <td>{{ $k }}.</td>
            <td class="text-left">{{ $app->scname[$k] }}</td>
            <td><input id="sc{{$k}}" name="sc{{$k}}" type="number" limit="100" class="form-control" style="text-align:right;" value="{{ $sc }}"></td>
          </tr>
            @endfor
          <tr>
            <td colspan=2 align=center>
              <select id="evdecision" name="evdecision" class="form-control" >
                <option value=0>-Final Decision-</option>
                <option value=1 class="text-primary">Accepted</option>
                <option value=5 class="text-info">Interested</option>
                <option value=9 class="text-danger">Rejected</option>
              </select>
            </td>
            <td><input type="number" class="form-control" style="text-align:right;" id='sc0' value="{{ $app->sc }}" readonly></td>
          </tr>
      </table>
      <textarea class="form-control" Placeholder="Memo" id="evsrem" name="evsrem">{{ (isset($evs) ? $evs->rem : '') }}</textarea>
      </form>
    </div> <!-- right col -->  
  </div> <!-- main row -->  
  <div id="modalpart"> 
    @include('partials.modal')
  </div> <!-- modalpart -->
</div>  <!-- container -->
@endsection

@section('script')
<script>
  $(document).ready(function () {
    $('#appamt, #apprem').on('change', function() {$('#submitafter').show();});
    $('#addinterviewer').on('click', function() {
      // get boss/hr list for selection and post to modal
      $.get('/interviews/list/{{ $rq->jobid }}')
        .done(function(data) {
          $('#ModalBody').empty().html(data);
        });
      $('#ModalLongTitle').empty().html('List of Valid Interviewer (Upper Job Position or HR)').addClass('h3');
      $('#btnsave').text('Select');
    });

    $('#btnsave').on('click', function() {
      var lists = $('#interviewers :selected')
        .map(function(){ return this.value }).get().join(", ");
      console.log(lists);
      $.ajax({
        method: "GET",
        url: '/interviews/create',
        data: {
  //        _token : "{{ csrf_token() }}",
          appid: {{ $app->id }},
          emplist : lists
        },
        success : function(data) {
          location.reload();
        }
      });
    });

    $('#sc1,#sc2,#sc3,#sc4').on('change', function() {sumscore();});
    $('#evname').on('change', function() {
      inputscore();
      getScore();
    });
    $('#evdecision').on('change',function() {
      if (confirm('Do you want to save ?')) {
        $.ajax({
          method : "POST",
          url : "/evdecide",
          data : $('#evappscores').serialize()
        }).done(function(data) {
          location.reload();
        });
      }
    });
    $('#submitafter').hide();

    inputscore();
    getScore();
  });

  function inputscore() {
      $evid = $('#evname').val();
      if ($evid > 0 ) {
        $('#evappscores input[id!=sc0], #evdecision').removeAttr('readonly')  ;
      } else {
        $('#evappscores input, #evdecision').attr('readonly', 'readonly');
      }
  }
  function sumscore() {
    $sc=0;
    for($i=1;$i<5;$i++) {
      $s = $('#sc'+$i).val()*1 ;
      $sc += ($s>0?$s: 0);
    }
    $('#sc0').val($sc);
  }
  function getScore() {
    $data = $('#evname option:selected').attr('data-value').split(',');
    $scs = 0;
    for($i=1;$i<5;$i++) {
      $('#sc'+$i).val($data[$i]);
      $scs += parseInt($data[$i]);
    }
    $('#sc0').val($scs);
    $('#evdecision').val($data[0]);
    if ($("#evname").val() > 0) {
      $('#evname').css('background', $('.btn-info').css('background'));
      $('#evname option').css('background', '#fff');
      $('#evdecision').val( $data[0] );
      $('#evsrem').val($('#evname option:selected').attr('data-rem')).show();
    } else {
      $('#evname').css('background', '#fff');
      $('#evdecision').val( {!! (isset($evs) ? $evs->state : 0) !!} );
      $('#evsrem').hide();
    }
  }
</script>
@endsection
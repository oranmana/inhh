@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col">
      <h2><a href="{!! url('/hrrq') !!}" title='Back'>HR Application</a></h2>
    </div>
    <div class="col text-right">
      <a class="btn btn-sm btn-{!! ($rq->opened ? "info" : "danger") !!} dirmodal" id="btnadd" data-id="0" data-toggle="modal" data-target="#ModalLong">
        {!! ($rq->opened ? "Add" : "Recruited") !!}</a>
    </div>
  </div>
  <table class="table table-sm">
    <tr class="info">
      <th>Job Title : </th>
      <td >{{ $rq->job->name }}</td>
      <th>Required by : </th>
      <td >{!! ($rq->rqdate ? date('d-M-Y', strtotime( $rq->rqdate)) : '') !!}</td>
      <th>Under : </th>
      <td >{{ $rq->doc->doccode ?? '' }}</td>
      <th>Status : </th>
      <td class="text-center"><span class="btn btn-{!! $rq->status['color'] !!}">{{ $rq->status['name'] }}</span></td>
    </tr>
  </table>

    @if (count($apps))
    <table class="table table-striped table-hover">
      <thead>
        <th>App ID</th>
        <th>Applicant</th>
        <th>Age</th>
        <th>Interview</th>
        <th>Score</th>
        <th>@</th>
      </thead>
      </tbody>
        @foreach($apps as $app)
        <tr class="row_dir" >
            <td>
                <a href="/interview/{{ $app->id }}">{!! $rq->id . '-' . $app->id !!} </a>
            </td>
            <td>
                <a class='dirmodal' data-id="{{ $app->id }}" data-toggle="modal" data-target="#ModalLong">{{ $app->dir->name }}</a>
            </td>
            <td>
                {{ $app->dir->age }} 
            </td>
            <td>
                @php($wdt = ($app->wdate ? strtotime($app->wdate) : 0))
                <a class='interview'>{!! ($wdt >0 ? 
                  (date('Hi', $wdt) > 0 ? date('H:i', $wdt) : '') 
                  . date('(D) d/M', strtotime($app->wdate))  
                : '-n/a-') !!}</a>
            </td>
            <td align=right>
                {!! $app->score !!}
            </td>
            <!-- xif ($rq->state < 3) -->
            <td>
              <button data-id="{{ $app->id }}" class="btn btn-sm btn-primary btnselect" data-toggle="modal" data-target="#ModalLong">Select</span>
            </td>
            <!-- xelse -->
            <td>
              <span class="badge badge-{!! ($app->rcid ? 'primary' : 'secondary') !!}">{!! ($app->rcid ? 'Selected' : 'Unselected') !!}</span>
            </td>
            <!-- xendif -->
        </tr>
        @endforeach
      </tbody>
    </table>
    <!-- <div class="modal fade" id="DirModal" tabindex="-1" role="dialog" aria-labelledby="DirModalLabel" aria-hidden="false">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="DirModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div id="DirModalBody" class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="btnsave">Save changes</button>
            <button type="button" class="btn btn-danger" id="btnclose" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div> -->
    </div>    
    @else
    <center><h3>No Data Found</h3></center>
    @endif
    @include('partials.modal')
</div>
<!-- ///////////////////////////////////////////////// -->

<div class="card" style="width: 18rem;margin:auto auto;">
  <div class="card-body">
    <h5 class="card-title">Pending<hr></h5>
    <p class="card-text">
    HR TM to make Final Decision here to select one applicant for the Request.
    <ul>Action for Selection
    <li>Update Applicant Status</li>
    <li>Close the Request</li>
    <li>Generate PP</li>
    <li>Create <span>Employment Contract</span> </li>
    </ul>
    </p>
  </div>
</div>
@endsection
@section('script')
<script >
  $(document).ready(function () {
    $('.dirmodal').on('click', function() {
      let appid = $(this).attr('data-id');
      let url = "{{ url('/appdir') }}" + '/{{ $rq->id }}/' + appid;
      $.ajax({
        url: url,
        type: 'GET',
        success: function(data) {
          //console.log(data);
          $('.modal-dialog').addClass('modal-lg');
          $('#ModalLongTitle').text( (data.dirid > 0 ? 'Applicant : ' + data.name : 'New Applicant'));
          $('#ModalBody').empty().html(data.body);
        }
      });
    });
    
    $('.btnselect').on('click', function(event) {
      event.preventDefault();
      $.ajax({
        type: "GET",
        url : "{{ url('/app/select') }}",
        data : {appid : $(this).attr('data-id')},
        success: function(msg) {
          console.log(msg);
          $('.modal-dialog').addClass('modal-lg');
          $('#ModalLongTitle').html( msg.title );
          $('#ModalBody').empty().html( msg.body );
          $('.modal-footer').hide();
        }
      });
    });

    $('#btnsave').on('click', function(event) {
      event.preventDefault();
      $.ajax({
        type: "POST",
        url : '/appdir/save',
        data : $('#dirmodalform').serialize(),
        success: function(msg) {
          $('#btnclose').trigger('click');
          location.reload();
        }
      });
    });
    $("#rqid").val({{ $rq->id }});
  });
  
  function finddir(taxid) {
    $("#rqid").val({{ $rq->id }});
    $.ajax({
      url: "{{ url('/dir') }}",
      type: "GET",
      data : {rqid: {{$rq->id}}, taxid : taxid},
      success: function(dir) {
        let res=dir;
        console.log(dir.error);
        let error = dir.error;
        let msg = '';
        error.forEach(function (err) {
          console.log(err);
          msg += (err==1 ? '- Employee not allowed to apply\n' : '')
            + (err==2 ? '- Applicant has applied\n' : '')
            + (err==3 ? '- Wrong ID Number\n' : '');
        });
        // console.log(msg);
        if (error.length) {
          // alert(msg);
          $("[name='dirid']").val(0);
          $("[name='code']").val('');
          $("[name='name']").val('');
          $("[name='tname']").val('');
          $("[name='bdate']").val('');
          $("[name='tel']").val('');
          $("[name='email']").val('');
          $("[name='rem']").val('');
        } else {
          $("[name='dirid']").val(dir.id);
          $("[name='sex']").val(dir.sex);
          $("[name='name']").val(dir.name);
          $("[name='tname']").val(dir.tname);
          $("[name='bdate']").val(dir.bdate);
          $("#age").val(dir.age);
          $("[name='tel']").val(dir.tel);
          $("[name='email']").val(dir.email);
          $("[name='rem']").val(dir.rem);

          $("[name='tax']").val(dir.eduyr);
          $("[name='zdir']").val(dir.edu);
          $("[name='pic']").val(dir.inst);
          $("[name='appby']").val(dir.gpa);
          $("[name='pic']").val(dir.inst);

          $("[name='reg']").val(dir.eng);
          $("[name='cty']").val(dir.ms);
          $("[name='appdate']").val(dir.it);
        }
      } // sucess
    }); // ajax
  }

</script>
@endsection

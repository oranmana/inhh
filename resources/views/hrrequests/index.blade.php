@extends('layouts.app')

@section('content')
<!-- hrrequests.index -->
<div class="container">
  <div class="dropdown">
    <button class="btn dropdown-toggle btn-lg" type="button" id="yrs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      {{ $yr }} HR Request
    </button>
    <div class="dropdown-menu" aria-labelledby="yrs">
      @foreach ($yrs as $y)
      <a class="dropdown-item" href="{!! url('/') . '/hrrq/'. $y->yr . '/' . ($y->yr == date('Y') ? $open : '0') !!}">{{ $y->yr }}</a>
      @endforeach
    </div>
  </div>
  <div class="row">
    <div class="col">
      <a class="btn{!!($open==1 ? ' btn-primary"' : '"')!!} href="/hrrq/{{$yr}}/1" role="button">Opened</a>  
      <a class="btn{!!($open==0 ? ' btn-primary"' : '"')!!} href="/hrrq/{{$yr}}/0" role="button">Closed</a>
    </div>
    <div class="col pull-right text-right">
      <a id="addbtn" class="badge badge-info" data-toggle="modal" data-target="#ModalLong">Add</a>
    </div>
  </div>

    @if (count($rqs))
    <table class="table table-striped table-hover">
      <thead>
        <th>ID</th>
        <th>Date</th>
        <th>Required</th>
        <th>Document</th>
        <th>Job Title</th>
        <th>Remark</th>
        <th>@</th>
      </thead>
      </tbody>
        @foreach($rqs as $rq)
        <tr class="row_dir" >
            <td>
                <a href="/hrapps/{{ $rq->id }}">{{ $rq->id }} </a>
            </td>
            <td>
                {{ date('d-M-Y', strtotime($rq->CREATED_AT)) }}
            </td>
            <td>
                {{ date('d-M-Y', strtotime($rq->rqdate)) }}
            </td>
            <td>
                <a {!! isset($rq->docid) && isset($rq->doc->doccode) ? " href='docs/" . $rq->docid . "'>" . $rq->doc->doccode : ">-" !!}</a>
            </td>
            <td>
                @php ($count = $rq->apps->count())
                {!! $rq->job->name !!} 
                {!! ($count ? ' ('.$count.')' : '') !!}
            </td>
            <td>
                {{ $rq->des ?? ''}}
            </td>
            <td>
              @php ($close = $rq->rcid > 0)
              <div class='badge {!! $rq->status['color'] !!}'>{!! $rq->status['name'] !!}</div>
            </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
    <div class="text-center h3">No Data Found</div>
    @endif

    @include('partials.modal')

</div>
@endsection

@section('script')
<script>
  $(document).ready(function() {
    $('#ModalLongTitle').text('Add New HR Request');
    $('.modal-footer').hide();
      $.ajax({
        url: "{{url('/hrrq/create')}}",
        type: "GET",
        success: function(data) {
          $('#ModalBody').empty().html(data);
        } // sucess
      }); // ajax

    $('#jobid').on('change', function() {
      $.ajax({
        url: "{{ url('/job') }}",
        type: "GET",
        success: function(res) {
          $("#minage").val(res.minage);
          $("#maxage").val(res.maxage);
          $("#eduid").val(res.eduid);
          $("#jobdes").val(res.jobdes);
          $("#wage").val(res.wage);
        } // sucess
      }); // ajax
    }); 
    
  }); 

</script>
@endsection
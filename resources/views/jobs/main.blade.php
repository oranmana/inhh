@extends('layouts.app')

@section('content')
<div class="container" id="assets">
  <div class="h4">Job Title Table</div>
  <div class="row">
    <div class="col" style="height:500px;overflow:scroll;">
      @include('jobs.jobchart')
    </div>
    <div id="emplist">
    @if(! empty($emps) )
      @include('jobs.emplist')   
    @endif
    </div>
  </div>
  <div id="contextMenu" class="dropdown-menu" data-id="0" ></div>
  @include('partials.modal')
</div>

@endsection

@section('script')
<script>
  $('document').ready(function() {
    var dragvalue = '';
    var $contextMenu = $("#contextMenu");
    var contextMenuBody = 
      "<div class='dropdown-item'><a class='submenu' id='addJob' data-toggle='modal' data-target='#ModalLong'>Add New Under</a></div>" + 
      "<div class='dropdown-item'><a class='submenu' id='renameJob' data-toggle='modal' data-target='#ModalLong'>Rename</a></div>" + 
      "<div class='dropdown-item'><a class='submenu' id='removeJob'>Remove</a></div>" +
      "<div class='dropdown-item'><a class='submenu' data-dismiss='modal' style='color:red'>Exit</a></div>";
    $('#contextMenu').html(contextMenuBody);

    $("body").on("contextmenu", ".job", function(event) {
      event.stopPropagation();
      
      // set id for context
      $("#contextMenu").attr('data-id', $(event.target).attr('data-id') );
  
      // set position of context to pointer
      $contextMenu.css({
        display: "block",
        left: event.pageX,
        top: event.pageY
      }).show();

      $('.submenu').show();
      if( $(this).hasClass('topjob') || $(this).next().length ||  $(this).attr('child') != undefined || $(this).attr('empnums') != undefined ) {
        $('#removeJob').parent().hide();
      } 
      if($(this).hasClass('topjob') ) {
        $('#renameJob').parent().hide();
      }

      $('.submenu').off("click");
      $('.submenu').on("click", function(event) {
        event.stopPropagation();
        event.preventDefault();
        var thisid = $(this).attr('id');
        var dataid = $('#contextMenu').attr('data-id');
        if ( thisid == 'addJob' ) { addJob(dataid); }
        if ( thisid == 'removeJob' &&  confirm( 'Unrecoverable ! Are you sure ?') ) {
          relocate(dataid, 0);
        }
        if ( thisid == 'renameJob' ) { renameJob(dataid); }
        $('#contextMenu').hide();
      });

      return false;
    });    

    // Tree Controll
    $('.mark').css('font-size','1.2em');
    $('ul.tree').css('list-style-type','none').css('margin-left','-10px');
    // $('ul.tree').css('list-style-type','none').css('font-size','0.9em').css('margin-left','-10px');

    $('.mark').on('click',function() {
      var myli = $(this).closest('li'); 
      if( $(myli).attr('child') ) {
        $(myli).find('ul').toggle();
        var onstate = $(this).text() == "▾";
        $(this).html( onstate ? "▸"  : "▾" );
      }
    });

    $('.tree').filter(function() {
      return $(this).attr("level-id") > 4;
    }).hide();

    $(".tree")
    .filter(function() { 
      $(this).attr('level-id') < 2;
    }).find('li').filter(function() {
      $(this).attr('child') > 0; 
    }).find(".mark").html("▸");
    // end Tree Control //    

    // job drop control
    $('.job')
      .on('dragstart', function(event) {
        dragvalue = [ $(this).attr('data-id'), $(this).text() ];
      })
      .on('dragover', function(event) {
        event.preventDefault();
      })
      .on('drop',function(event) {
        event.preventDefault();
        event.stopPropagation(); 
        var newparid = $(this).attr('data-id');
        if( confirm( 
          'Move ' + dragvalue[1] + ' to be under ' + $(this).text() 
        ) ) {
          relocate(dragvalue[0], $(this).attr('data-id'));
        }
      });

    $('.modal-header').addClass('bg-primary');

    $('.job').on('click', function() {
      viewjob( $(this).attr('data-id') );
    });
    // $('#emplist').hide('slide', {direction: "right"}, 1000);
    $('#emplist').slideUp();


  }); // end of doc.ready()

  function viewjob(orgid) {
    $.ajax({
      method: "GET",
      url : "{{ url('jobs')}}"+"/"+orgid
    }).done(function(data) {
//      console.log(data);
      $('#emplist').empty().html(data);
      $('#profile').off('click');
      $('#profile').on('click', function() {
        var dataid = $(this).attr('data-id');
        $.ajax({
          method : "GET",
          url : "{{ url('/job/profile') }}" + "/" + dataid
        }).done(function(data) {
          $('.modal-lg').removeClass('modal-lg');
          $('#ModalLongTitle').html( 'Organization : ' + data[1] + '<br>Report to : ' + data[0] );
          var wh = window.innherHeight;
          wh = wh * 75 / 100;
          $('#ModalBody').css({'height':'400px','overflow':'scroll'});
          $('#ModalBody').empty().html(data[2]);
          $('#btnsave').hide();
        });
      });
    });
  }

  function viewempjob(empid) {
    $.ajax({
      method : "GET",
      url : "{{ url('jobs/emp') }}" + '/' + empid
    }).done(function(data) {
      $('#ModalLong').addClass('modal-lg');
      $('#ModalLongTitle').html('Employee Promotions').addClass('h3');
      $('#ModalBody').html(data[1]);
//      $('#ModalLong').modal('show');
    });
  }

  function relocate(jobid, parid) {
    var relocate = (parid == 0);
    $.ajax({
      method : "POST",
      url : "{{ url('/job/relocate') }}",
      data : {
        _token : "{{ csrf_token() }}",
        jobid : jobid,
        parid : parid
      },
      success : function(data) {
        alert(data[1]);
        if (data[0] != 'error') location.reload();
      }
    });
  }

  function addJob(jobid) {
    $.ajax({
      method : "GET",
      url : "{{ url('job/add') }}"+"/"+jobid
    }).done(function(data) {
      $('#ModalLong').modal('show');
      $('.modal-lg').removeClass('modal-lg');
      $('#ModalLongTitle').html('New Job Title');
      $('#btnsave').removeAttr('data-dismiss').val('Add');
      $('.error').css('margin',0).hide();
      $('#btnsave').on('click', function(event) {
        event.stopPropagation();
        event.preventDefault();
        var formdata = $('form#addjobtitle').serialize();
        $.ajax({
          method : "POST",
          url :  "{{ url('/job/add') }}",
          data : formdata,
          dataType : 'jason',
          error : function(xhr, status, error ) {
//            console.log(xhr, status, error);
            if (xhr.status==200) {
              alert('New Unit Added Successfully !');
              $('#ModalLong').modal('hide');
              location.reload();
            } else {
              var errors = JSON.parse(xhr.responseText)['errors'];
              $('.error').each(function() {
                var nm = $(this).attr('id').replace('error_','');
                var s = $('#error_'+nm);
                if (errors[nm]) $(s).hide();
                $(s).empty().html( errors[nm] ? '*' + errors[nm] : '');
                if (errors[nm]) $(s).show();
                // console.log(s, nm, errors[nm]);
              });
              // console.log(xhr, error, status, errors);
            }
          }
        });
      });
      $('#ModalBody').empty().html(data);
    });
  }
  
  function renameJob(jobid) {
    $.ajax({
      method : "GET",
      url : "{{ url('/job/rename') }}" + "/" + jobid
    }).done(function(data) {
      $('#ModalLong').modal('show');
      $('.modal-lg').removeClass('modal-lg');
      $('#ModalLongTitle').html('Rename Job Title');
      $('#btnsave').text('Rename');
      $('#btnsave').on('click', function() {
        $('form#renamejob').submit();
      });
      $('#ModalBody').empty().html(data);
    });
  }
   
</script>
@endsection
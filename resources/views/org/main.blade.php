@extends('layouts.app') 

@section('content')
<div class="container" id="assets">
  <div class="row">
    <div class="col-md-4">
      @include('org.orgchart')
    </div>
    <!-- end of chart -->
    @if(!empty($errors->first()))
    <div class="row col-lg-8">
        <div class="alert alert-danger">
            <span>{{ $errors->first() }}</span>
        </div>
    </div>
    @endif    
    
    <div id="emplist" class="col-md-8">
    @if(! empty($emps) )
      @include('org.orglist')
    @endif
    </div>
  </div>
  
  <div id="contextMenu" class="dropdown-menu" data-id="0" ></div>
  @include('partials.modal')

</div> 
<!-- end of container -->

@endsection

@section('script')
<script>
  $('document').ready(function() {
    var dragvalue = '';
    var $contextMenu = $("#contextMenu");
    var contextMenuBody = 
      "<div class='dropdown-item'><a class='submenu' id='addOrg' data-toggle='modal' data-target='#ModalLong'>Add New Under</a></div>" + 
      "<div class='dropdown-item'><a class='submenu' id='renameOrg' data-toggle='modal' data-target='#ModalLong'>Rename</a></div>" + 
      "<div class='dropdown-item'><a class='submenu' id='removeOrg'>Remove</a></div>" +
      "<div class='dropdown-item'><a class='submenu' data-dismiss='modal' style='color:red'>Exit</a></div>";
      $('#contextMenu').html(contextMenuBody);
   
    $("body").on("contextmenu", ".org", function(event) {
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
      if( $(this).hasClass('toporg') || $(this).next().length ||  $(this).attr('child') != undefined || $(this).attr('empnum') != undefined ) {
        $('#removeOrg').parent().hide();
      } 
      if($(this).hasClass('toporg') ) {
        $('#renameOrg').parent().hide();
      }

      $('.submenu').off("click");
      $('.submenu').on("click", function(event) {
        event.stopPropagation();
        event.preventDefault();
        var thisid = $(this).attr('id');
        var dataid = $('#contextMenu').attr('data-id');
        if ( thisid == 'addOrg' ) { addOrg(dataid); }
        if ( thisid == 'removeOrg' &&  confirm( 'Unrecoverable ! Are you sure ?') ) {
          relocate(dataid, 0);
        }
        if ( thisid == 'renameOrg' ) { renameOrg(dataid); }
        $('#contextMenu').hide();
      });

      return false;

    });    

    // Tree Controll
    $('.mark').css('font-size','1.2em');
    $('ul.tree').css('list-style-type','none');
    // .css('font-size','0.9em').css('margin-left','-10px');

    $('.mark').on('click',function() {
      var myli = $(this).closest('li');
      if( $(myli).attr('child') ) {
        $(myli).find('ul').toggle();
        var onstate = $(this).text() == "▾";
        $(this).html( onstate ? "▸"  : "▾" );
      }
    });
    
    $('.org')
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
          'Move ' + dragvalue[0] + ' ' + dragvalue[1] + ' to be under ' + newparid + ' ' + $(this).text() 
        ) ) {
          relocate(dragvalue[0], $(this).attr('data-id'));
        }
      });

    // hide small tree
    $('.tree').filter(function() {
      $(this).attr("level-id") > 4;
    }).hide();

    $('.tree').filter(function() {
      $(this).attr("level-id") < 2;
    })
    .find('li').filter(function() {
      $(this).attr('child') > 0; 
    })
    .find(".mark").html("▸");
    // end Tree Control //    

    $('.modal-header').addClass('bg-primary');

    $('.org').on('click', function() {
      vieworg( $(this).attr('data-id') );
    });

    // #profile handling

  }); // End of Doc.Ready()

  function vieworg(orgid) {
    $.ajax({
      method: "GET",
      url : "{{ url('orgs')}}"+"/"+orgid
    }).done(function(data) {
      console.log(data);
      $('#emplist').empty().html(data);
    
      $('#profile').off('click');
      $('#profile').on('click', function() {
        var dataid = $(this).attr('data-id');
        $.ajax({
          method : "GET",
          url : "{{ url('/org/profile') }}" + "/" + dataid
        }).done(function(data) {
          $('.modal-lg').removeClass('modal-lg');
          $('#ModalLongTitle').html('Under : ' + data[0]);
          $('#ModalBody').empty().html(data[1]);
          $('#btnsave').hide();
        });

      });
    })
  }

  function relocate(orgid, parid) {
    var relocate = (parid == 0);
    $.ajax({
      method : "POST",
      url : "{{ url('/org/relocate') }}",
      data : {
        _token : "{{ csrf_token() }}",
        orgid : orgid,
        parid : parid
      },
      success : function(data) {
        location.reload();
      }
    });
  }

  function addOrg(orgid) {
    $.ajax({
      method : "GET",
      url : "{{ url('org/add') }}"+"/"+orgid
    }).done(function(data) {
      $('#ModalLong').modal('show');
      $('.modal-lg').removeClass('modal-lg');
      $('#ModalLongTitle').html('New Organization Unit');
      $('#btnsave').removeAttr('data-dismiss');
      $('.error').css('margin',0).hide();
      $('#btnsave').val('Add').on('click', function() {
        // $('form#addorg').submit();
        event.preventDefault();
        var data = $('form#addorg').serialize();
        $.ajax({
          method : "POST",
          url :  "{{ url('/org/addorg') }}",
          data : data,
          dataType : 'jason',
          success : function(res) {
            console.log(data);
          },
          error : function(xhr, status, error ) {
//            $('#ModalLong').modal('show');
            var errors = JSON.parse(xhr.responseText)['errors'];
            $('.error').each(function() {
              var nm = $(this).attr('id').replace('error_','');
              var s = $('#error_'+nm);
              if (errors[nm]) $(s).hide();
              $(s).empty().html( errors[nm] ? '*' + errors[nm] : '');
              if (errors[nm]) $(s).show();
              console.log(s, nm, errors[nm]);
            });
            // foreach(errors, function(x,y) {
            //   var n = 'error_' + x;
            //   $(n).empty().html(y);
            // });
            console.log(xhr, error, status, errors);
          }
        });

      });
      $('#ModalBody').empty().html(data);
    });
  }

  function renameOrg(orgid) {
    $.ajax({
      method : "GET",
      url : "{{ url('/org/rename') }}" + "/" + orgid
    }).done(function(data) {
      $('#ModalLong').modal('show');
      $('.modal-lg').removeClass('modal-lg');
      $('#ModalLongTitle').html('Rename Organization');
      $('#btnsave').val('Rename').on('click', function() {
        $('form#renameorg').submit();
        // var data = $('form#renameorg').serialize();
      });
      $('#ModalBody').empty().html(data);
    });
  }

</script>
@endsection
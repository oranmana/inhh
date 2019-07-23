@extends('layouts.app')

@section('content')
<div class="container" id="assets">
  <div class="row">
    <div class="col-md-2">
      <a class="h3 dropdown" href='#' id="statemenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Asset Library
      </a>
      <div class="dropdown-menu"  aria-labelledby="statemenu">
        <a class="dropdown-item" href="{{ url('assets/0/0/0/4') }}">Spare</a>
        <a class="dropdown-item" href="{{ url('assets/0/0/0/5') }}">Lost</a>
        <a class="dropdown-item" href="{{ url('assets/0/0/0/6') }}">Out of Date</a>
        <a class="dropdown-item" href="{{ url('assets/0/0/0/7') }}">Out of Order</a>
        <a class="dropdown-item" href="{{ url('assets/0/0/0/8') }}">Disposable</a>
        <a class="dropdown-item" href="{{ url('assets/0/0/0/9') }}">Disposed</a>
      </div>
    </div>
    <input type="hidden" id="fnitem" value="">
    <label for="findTeam" class="form-control col-md-1 btn-secondary">Team : </label>
    <select class="form-control col-md-3" id="findTeam">
      <option value="0">-All Team-</option>
      @foreach($teams as $team)
        <option value="{{ $team->id }}"{!! ($team->id == $fnteam ? " SELECTED" : "") !!}>{{ $team->name }}</option>
      @endforeach
    </select>
    <label for="findTeam" class="form-control col-md-1 btn-secondary">PIC : </label>
    <select class="form-control col-md-3" id="findPic">
      <option value="0">-All PICs-</option>
      @foreach($pics as $pic)
        <option value="{{ $pic->id }}"{!! ($pic->id == $fnpic ? " SELECTED" : "") !!}>{{ $pic->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="row">
    <div class="col-md-3">
      @include('assets.itemlist')
    </div>
    <div class="col-md-9">
      @include('assets.list')
    </div>
  </div>
  @include('partials.modal')
</div>


@endsection

@section('script')
<script>
  $('document').ready(function() {

    // Tree Control //
    $('.mark').css('font-size','1.2em');
    $('ul.tree').css('list-style-type','none').css('font-size','0.9em');
    $('.modal-header').addClass('bg-primary');

    $('.mark').on('click',function() {
      var myli = $(this).closest('li');
      if( $(myli).attr('child') ) {
        $(myli).find('ul').first().toggle();
        var onstate = $(this).text() == "▾";
        $(this).html( onstate ? "▸"  : "▾" );
      }
    });
    
    $('.mark').next('a').on('click', function() {
      $('#fnitem').val($(this).closest('li').attr('data-id'));
      window.location = "{{ url('assets') }}" + '/' + $('#fnitem').val() + '/' + $('#findTeam').val() + '/' + $('#findPic').val();
    } );   
    
    $(".tree[level-id=0] > li").filter(function() { 
      $(this).attr('child') > 0; 
    }).find(".mark").html("▸");

    $('.tree').filter(function() {
      return $(this).attr('level-id') > 0;
    }).hide();

    // end Tree Control //

    $('#findTeam, #findPic').on('change', function() {
      window.location = "{{ url('assets') }}" + '/0/' + $('#findTeam').val() + '/' + $('#findPic').val();
    });

    $('.datarow').on('click', function() {
      $.ajax({
        method : "GET",
        url : "{{ url('asset') }}" + '/' + $(this).attr('data-id')
      }).done(function(data) {
        $('#btnsave').hide();
        $('#ModalLongTitle').empty().html(data[0]);
        $('#ModalBody').empty().html(data[1]);
//        $('#ModalLong').addClass('modal-lg');
      });
    });
    
  });

</script>
@endsection
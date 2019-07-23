@extends('layouts.app')
@section('content')
@php($zoneid = (isset($zoneid) ? $zoneid : 0) )
@php($zones = \App\Models\Country::Zones()->get() )
<div class="container">
  <div class="h3">Countries Name</div>

  <div class="row">
    <label class="col-3">Zone</label>
    <label class="col-3">Country</label>
    <label class="col-3">Sea Port</label>
    <label class="col-3">Air Port</label>
  </div>
  <div class="row">
    <select id="zone" class="form-control col-3" size=10 level=0> 
      @foreach($zones as $zone)
      <option class='zonename cname' value="{!! $zone->id !!}"{!! $zone->id == $zoneid ? " SELECTED" : "" !!}>{{ $zone->des }} : {{ $zone->name }}</option>
      @endforeach
    </select>

    <select id="country" class="form-control col-3" size=10 level=1>
    </select>

    <select id="seaport" class="form-control col-3" size=10 level=3>
    </select>

    <select id="airport" class="form-control col-3" size=10 level=2>
    </select>

  </div>

  <div id="bodytable"></div>

  @include('partials.modal')
</div>
@endsection

@section('script')
<script>
  $(document).ready(function() {

    $('.zonename').off().on('click',function() {
      var zoneid = $(this).val();
      $.when( getoption( $(this).val() , 1, 'country') )
        .done( setcountry() );
    });

    setcountry();

    $('.zonename').first().click();
  });

  function setprofile() {
    $('.cname').on('click', function() {
      var cur=0;
      var c = $(this).attr('class');
      if (c.indexOf('countryname') != -1) { cur=1;}
      if (c.indexOf('seaportname') != -1) { cur=2;}
      if (c.indexOf('airportname') != -1) { cur=3;}
//      console.log(c, cur);
      openform( $(this).attr('value'), cur );
    });
  }

  function openform(id, cur) {
    if(id==0) {
      par = $('#country').val();
      if (cur==1) {par = $('#zone').val(); }
    }
    $.ajax({
      type : "GET",
      url : "{{ url('country') }}" + "/" + id + (id==0 ? '/' + cur + '/' + par: '')
    }).done(function(data) {
      $('#bodytable').empty().html(data);
      // $('#btnupdate, #btnsave').hide();
      setform();
    });
  }

function setcountry() {
  $('.countryname').off().on('click', function() {
    event.stopPropagation();
    var countryid = $(this).val();
    getoption(countryid, 2, 'seaport');
    getoption(countryid, 3, 'airport');
    setprofile();
  });
}

function setform() {
  $('#btnsave, #btnupdate').hide();
  $('form#countryform input').on('change', function() {
    $('#btnsave, #btnupdate').hide();
    if ( $('#countryform #id').val() == 0 ) {
      if ( $("#countryform input[name='name']").val().length > 2 ) { $('#btnsave').show(); }
    } else {
      if ( $("#countryform input[name='name']").val().length > 2 ) { $('#btnupdate').show(); }
    }
  });
    $('#btnsave, #btnupdate').off().on('click', function() {
      $.ajax({
      type : "POST",
      url : "{{ url('country/save') }}",
      data : $('form#countryform').serialize()
    }).done(function(data) {
      if ( data == 0 ) {
        alert('Insuccessful record - Please try again');
      }

    })
  });
}

function getoption(parid,level,selectname) {
  $.ajax({
    type : "GET",
    url : "{{ url('countryof') }}" + "/" + level + "/" + parid 
  }).done(function(data) {
    $('#'+selectname).empty().append(data);
    if (level == 1) {
      setcountry();
    }
    setprofile();
  });
}

</script>
@endsection

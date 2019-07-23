function arrangehead() {
  var content= $('.tbl-content:first');
  $(content).css('height', (window.innerHeight - $(content).offset().top) + 'px' )
    .css('overflow','scroll');
  
    $('.tbl-header table tr.header th').each(
    function() { 
      $(this).addClass('text-left');
      $(this).css('width', 
        $('.tbl-content table tr:first td').eq( $(this).index() ).css('width') 
      );
    });   
}
function setthhead() {
  $('.tbl-header table tr th').each(
    function() { 
      var w = $('.tbl-header table tr:first td').eq( $(this).index() ).css('width');
      var w = $('.tbl-content table tr:first td').eq( $(this).index() ).css('width');
      $('.tbl-header table tr:first th').eq( $(this).index() ).css('width', w);
      // $(this).css('width', w);
      console.log( w, $(this).css('width') );
    });   
}
function welcome() {
  alert('Welcome');
}

function datetext(readdate, format) {
  var date = new Date(readdate);
  var ms = Array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
  var m = ms[date.getMonth()];
  var y = date.getFullYear();

  var h = date.getHours();
  var i = date.getMinutes();
  var s = date.getSeconds();

  var txt = date.getDate() + '-' + m + '-' + y;
  if (format==1) txt += ' (' + h + ':' + i + ')';
  return txt;
}

function datetextinit() {
  $('.datetext').on('focusin', 
    function() {
      $(this).datetextin();
    }
  );

  $('.datebox').on('focusout',
    function() {
      $(this).dateboxout();
    }
  );

  $('.datebox').hide();
}

////////////////////////////////Date/////////////////////
jQuery.fn.extend({
  datetextin: function() {
    // ".datetext" then dateeditbox
    var nx = $(this).next('.datebox');
    $(nx).show();
    $(nx).select();
    $(this).hide();
  },

  dateboxout: function() {
    var format = $(this).attr('dateformat')*1;
    var tx = datetext( $(this).val(), format );
    var pv = $(this).prev('.datetext');
    $(this).hide();
    $(pv).val(tx);
    $(pv).show();
    console.log(tx + '-' + pv.text());
  }

});


$('document').ready(
  function() {
    $('.datetext').on('focusin', 
      function() {
        $(this).datetextin();
      }
    );

    $('.datebox').on('focusout',
      function() {
        $(this).dateboxout();
      }
    );

    $('.datebox').hide();

  }
);
////////////////////////////////End Date/////////////////////
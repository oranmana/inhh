<div class="container">
@php ( $customers = App\Models\mkcustomer::Point()->orderby('name') )
  <form id='newinvoice' method="POST" action="{{ url('export/create') }}">
    {{ csrf_field() }}
    
    <div class="row">
      <div class="form-group col">
        <label for="etdate">Estimated B/L Date*</label>
        <input type="text" id="etddate" name="etd" class="form-control datetext" value="{{ date('d-M-Y') }}">
        <input type="date" id="etd" class="form-control datebox" value="{!! date('Y-m-d') !!}" required>
      </div>
      <div class="form-group col">
        <label for="sonum">SAP S/O Number</label>
        <input type="text" id="sonum" name="ponum" class="form-control" value="" >
      </div>
    </div>

    <label for="customerid">Customer*</label>
    <select name="customerid" id="customerid" class="form-control" required>
      <option value=0>-Select Customer-</option>
      @foreach($customers->get() as $cs)
      <option value="{{ $cs->id }}">{{ strtoupper($cs->name) }} [{{$cs->nm}}]</option>
      @endforeach
    </select>

    <div class="row">
      <div class="form-group col">
        <label for="ponum">Customer P/O Number</label>
        <input type="text" id="ponum" name="ponum" class="form-control" value="">
      </div>
      <div class="form-group col">
        <label for="pointid">Point-ID*</label>
        <select name="pointid" id="pointid" class="form-control" required>
          <option value=0>-Select Point ID-</option>
          @foreach($customers as $cs)
          <option value="{{ $cs->id }}">{!! $cs->name . ($cs->nm > '' ? '(' . $cs->nm . ')' : '') !!}</option>
          @endforeach
        </select>
      </div>
    </div>

    <label for="etdate">Port of Discharge</label>
    <input type="text" id="pod" class="form-control" value="" readonly>
    <div class="row">
      <div class="form-group col">
        <label>Price Term</label>
        <input type="text" id="priceterm" class="form-control" value="" readonly> 
      </div>
      <div class="form-group col">
        <label>Payment Term <span id="paytermid"></span></label>
        <input type="text" id="payterm" class="form-control" value="" readonly> 
      </div>
    </div>
  </form>
</div>

<script>
  $(document).ready(function () {
    $('#customerid').on('change', function() {
      var custid= $(this).val();
      rewritepointcustomer(custid);
    }); 
    $('#pointid').on('change', function() {
      var select = $(this).find('option:selected');
      var prcid = $(select).attr('prc');
      var payid = $(select).attr('pay');
      $('#pod').val($(select).attr('pod'));
      $('#priceterm').val(  prcid );
      $('#payterm').val( payid );
    }); 
    $('#btnsave').on('click', function() {
      var today = new Date;
      today.setHours(0,0,0,0);
      var ok = new Date($('#etddate').val()) > today
        && $('#customerid').val() > 0
        && $('#pointid').val() > 0;
      if (ok) {
        $.ajax({
          type : "POST",
          data : $('form#newinvoice').serialize(),
          url : "{{ url('export/save') }}"
        });
      } else {
        alert('Data incomple !')
      }
    });
    datetextinit();
  });    
  // Allow new pointid of other price and payment term
  function inspectpointid() {

  }

  function rewritepointcustomer(custid) {
    var url = "{{ url('export/pointfor') }}" + '/' + custid;
    $.ajax({
      type : "GET",
      url : url
    }).done(function (data) {
      $('#pointid').empty().html(data);
      $('#pointid').val(0);
    });
  }
</script>
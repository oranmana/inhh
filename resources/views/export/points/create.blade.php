<!--  ต้องมี Customerid กับ Countryid -->

@php ( $customer = App\Models\mkcustomer::find($customerid) )
@php ( $country = App\Models\country::find($countryid) )

  <form id='newpointid'>
    {{ csrf_field() }}
    <input type="hidden" name="customerid" value="{{ $customerid }}">

    <label for="customerid">Customer</label>
    <input type="text" class="form-control" value="{{ $customer->name }}">

    <label for="customerid">Country</label>
    <input type="text" class="form-control" value="{{ strtoupper($country->name) }}">

    <label for="etdate">Port of Discharge</label>
    <select name="portid" id="portid" class="form-control" value="0" required>
    @php($pts = App\Models\Country::OfCountry($countryid)->orderBy('name')->get())
    @foreach($pts as $pt)
      <option value="{{ $pt->id }}">{{ ucfirst($pt->name) }}</option>
    @endforeach
    </select>

    <label>Price Term</label>
    <select name="priceid" id="priceid" class="form-control" value="0">
    @php($pts = App\Models\priceterm::on()->get())
    @foreach($pts as $pt)
      <option value="{{ $pt->id }}">{{ $pt->name }}</option>
    @endforeach
    </select>

    <label>Payment Term <span id="paytermid"></span></label>
    <select name="payid" id="payid" class="form-control" value="0">
    @php($pys = App\Models\payterm::on()->get())
    @foreach($pys as $py)
      <option value="{{ $py->id }}">{{ $py->name }}</option>
    @endforeach
    </select>

  </form>

<script>

  $(document).ready(function () {
    
    $('#btnsave').on('click', function() {
      $.ajax({
        type : "POST",
        url : "{{ url('point/save') }}",
        data : $('form#newpointid').serialize()
      }).done(function(data) {
        if (data) {
          alert('Target data cannot be duplicated.')
        }
        var customerid = $('#customerid').val();
        var countryid = $('#countryid').val();
        var priceid = $('#priceterm').val();
        var payid = $('#payterm').val();

        var url = "{{ url('points') }}" + '/' + customerid + '/' + countryid + '/' + priceid + '/' + payid;
        location.href = url;
      })
    });

  });    

</script>
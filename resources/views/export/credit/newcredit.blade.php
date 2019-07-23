<!-- input $crid, $customerid -->
@php( $dirs = App\Models\mkcustomer::ASR()->NotDom()->orderBy('name')->select('id','name') )
@php( $inv = App\Models\mkinvoice::find($invid) )
@php( $crid = $inv->creditid )
@if($crid==0)
  @php( $cr = 0)
  @php( $dr = $arid)
@else
  @php( $cr = ($crid ? App\Models\mkcredit::find($crid) : 0) )
  @php( $dr = $cr->dirid )
@endif
@php( $tp = ($crid ? $cr->typeid : 4423) )
@php( $crtypes = App\Models\Common::CreditTypeforExport() )
@php( $mindate = date('Y-m-d', strtotime('-1 month')))
<div class="container">
  <form id="newcredit">
    {{ csrf_field() }}
    <input type="hidden" name="cr_id" value="{{ $crid }}">
    <input type="hidden" name="cr_arid" value="{{ $arid }}">

    <label>*Credit No.</label>
    <input type="text" class="form-control" name="cr_code" placeholder="Enter Reference No." value="" required>

    <label>*Issued By</label>
    <!-- <input type="text" class="form-control" name="crcode" value=""> -->
    <select name="cr_dirid" id="dirid" class="form-control" required>
      <option value=0>-Select-</option>
      @foreach($dirs->get() as $dir)
      <option value="{{ $dir->id }}"{!! $dir->id == $dr ? " SELECTED" : '' !!}>
        {{ strtoupper($dir->name) }} </option>
      @endforeach
    </select>

    <div class="row">

      <div class="form-group col">
        <label>Type</label>
        <select name="cr_type" id="crtypeid" class="form-control" required>
          @foreach($crtypes->get() as $crtype)
          <option value="{{ $crtype->id }}"{!! $crtype->id == $tp ? " SELECTED" : '' !!}>
            {{ strtoupper($crtype->name) }} </option>
          @endforeach
        </select>
      </div>

      <div class="form-group col">
        <label>Date of Issue</label>
        <input type="date" class="form-control" name="cr_date" value="{{ $mindate }}" required>
      </div>

    </div>

    <div class="row">
      <div class="form-group col">
        <label>Credit Term (Days)</label>
        <input type="number" class="form-control text-right" placeholder="At Sight" name="cr_days" value="0">
      </div>
      <div class="form-group col">
        <label>Amount</label>
        <input type="number" class="form-control text-right" name="cr_amt" value="0" required>
      </div>
    </div>

  </form>
</div>
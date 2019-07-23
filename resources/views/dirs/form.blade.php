@extends('layouts.app')

@section('content')
<div class="container">
  <h2><a href="{{ URL::previous() }}">Directory</a></h2>
  <form id=rec>
    <table class=table style=margin-bottom:0;>
      <tr>
        <td>
          <div class="form-group">
          <label for="id">Record ID</label>
          <input type="text" class="form-control" id="id" placeholder="Record ID" value="{{ $dir->id }}" readonly>
        </td>
        <td>
          <div class="form-group">
              <label for="par">Parent ID</label>
              <input type="text" class="form-control" id="par" placeholder="Enter Parent ID" value="{{ $dir->par }}" readonly>
          </div>
        </td>
        <td>
          <div class="form-group">
            <label for="type">Type</label>
            <select id=type class="form-control">
              <option value=0 {!! $dir->type==0?" selected":"" !!}>Organization</option>
              <option value=1 {!! $dir->type==1?" selected":"" !!}>Person</option>
            </select>
          </div>
        </td>
        <td>
          <div class="form-group">
            <label for="sex">{!! $dir->type ? "Sex" : "Category" !!}</label>
            <select id=sex class="form-control">
              <option value=0 {!! $dir->sex==0?" selected":"" !!}>{!! $dir->type ? 'Mr.' : 'Co.,Ltd' !!}</option>
              <option value=1 {!! $dir->sex==1?" selected":"" !!}>{!! $dir->type ? 'Ms.' : 'Ltd.,Part' !!}</option>
              <option value=2 {!! $dir->sex==2?" selected":"" !!}>{!! $dir->type ? 'Mrs.' : 'Part.' !!}</option>
            </select>
          </div>
        </td>
        <td>
          <div class="form-group">
            <label for="grp">Group</label>
            <select id=grp class="form-control">
              @php( $cms = App\Models\Common::DirExternal()->get() )
              @foreach($cms as $cm)
              <option value={{ $cm->id }}{!! $dir->grp == $cm->id ?" selected":"" !!}>{{ $cm->name }}</option>
              @endforeach
            </select>
          </div>
        </td>
        @if($dir->isThai ?? True)
        <td>
          <div class="form-group">
          <label for="code">Tax Code</label>
          <input type="text" class="form-control" id="code" placeholder="Record ID" value="{{ $dir->code }}">
        </td>
        @endif
      </tr>
    </table>
    <table class="table table-sm borderless mb-0 pb-0 ">
      <tr>
        <td>
          <div class="row form-group">
            <label for="name" class="col-2">Full Name</label>
            <input type="text" class="form-control col-10" id="name" placeholder="Enter Full Name" value="{{ $dir->name }}">
          </div>
        </td>
      </tr>
    </table>
    <table class="table table-sm  borderless mt-0 pt-0">
      <tr>
        <td class="w-50">
          <table class="table table-sm borderless">
            <tr>
              <td>
                <label for="name" >Short Name</label>
              </td>
              <td>
                <input type="text" class="form-control" id="name" placeholder="Enter Short Name" value="{{ $dir->nm }}">
              </td>
            </tr>
          </table>

          <div class="form-group">
            <label>Postal Address</label>
              <textarea class="form-control" id="address" placeholder="Postal Address" rows=5>
  {{ $dir->address }}
              </textarea>
          </div>
        </td>
        <td class="w-50">
          <table class="table table-sm table-borderless">
            <tr>
              @if( $dir->isThai ?? True) 
              <td>
                <label for="tname">ชื่อ (ภาษาไทย)</label>
              </td>
              <td>
                <input type="text" class="form-control" id="tname" placeholder="ชื่อ ภาษาไทย" value="{{ $dir->tname }}">
              </td>
                @else
              <td>
                <label for="nation">Nationality</label>
              </td>
              <td>
                <select id=nation class="form-control" >
                  <option value=0>-N/A-</option>
                  @foreach($countries->where('name','>','') as $cty)
                  <option value={{ $cty->id }}{!! $dir->nation == $cty->id ?" selected":"" !!}>{{ strtoupper(trim($cty->name)) }}</option>
                  @endforeach
                </select>
              </td>
                @endif
            </tr>
            <tr>
              <td>
                <label for="tel">Tel</label>
              </td>
              <td>
                <input type="text" class="form-control" id="tel" placeholder="Enter Telephone Number" value="{{ $dir->tel }}">
              </td>
            </tr>
            <tr>
              <td>
                <label for="fax" >Fax</label>
                </td>
              <td>
                <input type="text" class="form-control" id="fax" placeholder="Enter Fax of LINE ID" value="{{ $dir->fax }}">
                </td>
            </tr>
            <tr>
              <td>
                <label for="email"  >E-mail</label>
              </td>
              <td>
                <input type="text" class="form-control" id="email" placeholder="Enter E-mail Address" value="{{ $dir->email }}">
              </td>
            </tr>
          </table>
        </td>

      </tr>
    </table>
    <table class="table borderless">
      <tr>
        <td>
          <div class="form-group">
            <label for="iserp">SAP Group</label>
            <select id=iserp class="form-control">
              <option value=0 {!! $dir->iserp==0?" selected":"" !!}>-N/A-</option>
              <option value=1 {!! $dir->iserp==1?" selected":"" !!}>Non-Person</option>
              <option value=2 {!! $dir->iserp==2?" selected":"" !!}>Person</option>
            </select>
          </div>
        </td>
        <td>
          <div class="form-group">
            <label for="erpdir">{!! $dir->iserp==1 ? "Organization" : "SAP Group" !!}</label>
            <select id=erpdir class="form-control">
              <option value=0 {!! $dir->erpdir==0?" selected":"" !!}>-N/A-</option>
              <option value=1 {!! $dir->erpdir==1?" selected":"" !!}>{!! $dir->iserp==1 ? 'Organization' : 'Employee' !!}</option>
              <option value=2 {!! $dir->erpdir==2?" selected":"" !!}>{!! $dir->iserp==1 ? 'Machine' : 'Customer' !!}</option>
              @if ($dir->iserp==2)
              <option value=3 {!! $dir->erpdir==2?" selected":"" !!}>Vendor</option>
              <option value=5 {!! $dir->erpdir==2?" selected":"" !!}>General</option>
              @endif
            </select>
          </div>
        </td>
        <td>
          <div class="form-group">
            <label for="erpfi">FI Vendor</label>
            <input type="text" class="form-control" id="erpfi" placeholder="Enter FI Vendor" value="{{ $dir->erpfi }}">
          </div>
        </td>
        <td>
          <div class="form-group">
            <label for="erpmm">MM Vendor</label>
            <input type="text" class="form-control" id="erpmm" placeholder="Enter MM Vendor" value="{{ $dir->erpmm }}">
          </div>
        </td>
        <td>
          <div class="form-group">
            <label for="erpsd">SD Customer</label>
            <input type="text" class="form-control" id="erpsd" placeholder="Enter SD Customer" value="{{ $dir->erpsd }}">
          </div>
        </td>
      </tr>
    </table>
    @if($dir->isInternal ?? True)
    <table class="table">
      <tr>
        <td>
          <div class="form-check">
              <label for="empid">Employee</label>
              <input type="text" class="form-control" id="empid" placeholder="Employee ID" readonly>
          </div>
        </td>
        <td>
          <div class="form-group">
              <label for="emprelative">Relation</label>
              <input type="text" class="form-control" id="emprelative" placeholder="Relationship">
          </div>
        </td>
        <td>
          <div class="form-group">
              <label for="empcat">Category</label>
              <input type="text" class="form-control" id="empcat" placeholder="Catergory">
          </div>
        </td>
      </tr>
    </table>
    @endif
    <button id="btnsubmit" type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
      $('.table-borderless th, .table-borderless td, .borderless th, .borderless td')
        .css('border', '0');
      $('#btnsubmit').hide();

  });
</script>
@endsection

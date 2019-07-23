@php( $item = \App\Models\PayrollItem::find($packid) )
<div class="h2">Payroll Item Profile</div>
<form id="payitemform" method="POST" action="">
  <div class="row">
    <label class="col-3">ID</label>
    <input type="text" name="id" value="{{ $item->id ?? 0 }}" class="form-control col-9">
  </div>
  <div class="row">
    <label class="col-3">PAR ID</label>
    <input type="text" name="par" value="{{ $item->par ?? 0 }}" class="form-control col-9">
  </div>
  <div class="row">
    <label class="col-3">No.</label>
    <input type="text" name="num" value="{{ $item->num ?? 0 }}" class="form-control col-9">
  </div>
  <div class="row">
    <label class="col-3">Code</label>
    <input type="text" name="code" value="{{ $item->code ?? '' }}" class="form-control col-9">
  </div>
  <div class="row">
    <label class="col-3">Name</label>
    <input type="text" name="name" value="{{ $item->name ?? '' }}" class="form-control col-9">
  </div>
  <div class="row">
    <label class="col-3">ชือ</label>
    <input type="text" name="des" value="{{ $item->des ?? '' }}" class="form-control col-9">
  </div>
  <div class="row">
    <label class="col-3">Applied to</label>
    <div class="form-inline">
      <input type="checkbox" {!! $item->forKR ? " checked" : "" !!} id="forkr" class="mr-2"> 
      <label for="forkr">KR</label>
    </div>
    <div class="form-inline">
      <input type="checkbox" {{ $item->forOF ? " checked" : ''}} id="forof" class="ml-3 mr-2">
      <label for="forof">Staff</label>
    </div>
    <div class="form-inline">
      <input type="checkbox" {{ $item->forWF ? " checked" : ''}} id="forwf" class="ml-3 mr-2">
      <label for="forof">Worker</label>
    </div>
  </div>
  <div class="row">
    <label class="col-3">SAP G/L</label>
    <input type="text" name="gl" value="{{ $item->erp ?? '' }}" class="form-control col-9">
  </div>
  <div class="row">
    <label class="col-3">Also Post to</label>
    <input type="text" name="dbl" value="{{ $item->tname ?? '' }}" class="form-control col-9">
  </div>

</form>

<form method="post" action="{{ url('myleave') }}">
  <div class="row col">  
    <div class="btn btn-info h4 text-left">Leave Request Form</div>
    {{ @csrf_field() }}
    <input type="hidden" name="rs" value="0">
  </div>
  <input type="hidden" name="empid" value="{{ $emp->id }}">
  <div class="row">
    <label>Name / ชื่อพนักงาน</label>
    <input type="text" class="form-control" value="{{ $emp->fullname}} ">
  </div>
  <div class="row">
    <label>From / ตั้งแต่วันที่</label>
    <input type="text" class="form-control datetext" value="{{ date('d-M-Y') }}">
    <input type="date" class="form-control datebox" name="fromdate" value="{{ date('Y-m-d') }}">
  </div>
  <div class="row">
    <label>To / ถึงวันที่</label>
    <input type="text" class="form-control datetext" value="{{ date('d-M-Y') }}">
    <input type="date" class="form-control datebox" name="todate" value="{{ date('Y-m-d') }}">
  </div>
  <div class="row">
    <label>Type / ประเภทการลา</label>
    <select class="form-control" name="leavetype" id="leavetype">
      @php( $leaves = \App\Models\Leave::where('par', '!=', 9192)->where('off',0)->orderBy('par')->orderBy('num')->get() )
      @foreach($leaves as $lv) 
        <option value="{{ $lv->id }}" reason="{{ $lv->reasoned }}">({{ $lv->code }}) {{ $lv->name }} / {{ $lv->tname}} </option>
      @endforeach
    </select>
  </div>
  <div class="row">
    <label>Reason / เหตุผล</label>
    <textarea class="form-control" name="reason" id="reason" size=3>
    </textarea>
  </div>
  <div class="row mt-2">
    <button class="btn btn-danger mr-5">Cancel</button>
    <button class="btn btn-primary" role="submit">Request</button>
  </div>
</form>
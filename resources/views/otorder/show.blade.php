@php( $atn = \App\Models\AtWork::find($attnid) )
<form id="otorder" method="POST">
  {{ @csrf_field() }}
  <input type=hidden name="id" value="{{ $attnid }}">
  <input type=hidden name="caldate" id="date" value="{{ $atn->w_date }}">
  <label>Employee</label>
  <input class="form-control" type=text value="{!! $atn->emp->fullname . ' / ' . $atn->emp->thname . ' (' . $atn->emp->curjob->name . ')' !!}">
  <label>Date</label>
  <input class="form-control" id="wdate" type=text value="{{ date('d-M-Y (D)', strtotime($atn->w_date)) }}">
  <label>OT Order Code</label>
  <div class="form-row">
    <input class="form-control col-1 text-right" id="ot1" name="ot1" type="number" value="{{ $atn->w_ot1 }}">
    <select id="shift" name="shift" class="form-control col-2">
      @if($atn->emp->wf)
      <option value="3679" nm="A"{!! $atn->w_workid==3679 ? " selected" : '' !!}>Shift A</option>
      <option value="3680" nm="B"{!! $atn->w_workid==3680 ? " selected" : '' !!}>Shift B</option>
      <option value="3681" nm="C"{!! $atn->w_workid==3681 ? " selected" : '' !!}>Shift C</option>
      @else
      <option value="3678" nm="N"{!! $atn->w_workid==3678 ? " selected" : '' !!}>Normal</option>
      <option value="3696" nm="H"{!! $atn->w_workid==3696 ? " selected" : '' !!}>Holiday Work</option>
      @endif
      <option value="3683" nm="H"{!! $atn->w_workid==3683 ? " selected" : '' !!}>Holiday</option>
      @can('isHR')
      <option value="6712" nm="J"{!! $atn->w_workid==6712 ? " selected" : '' !!}>Work Injury</option>
      @endcan
    </select>
    <input class="form-control col-1 mr-3" id="ot2" name="ot2" type="number" value="{{ $atn->w_ot2 }}">
    <label> / </label>
    <input class="form-control col-1 ml-3 mr-3 text-center" id="ot3" name="ot3" type="number" value="{{ $atn->w_ot3 }}">
    <label> Hrs. after shift</label>
  </div>
  <label>Reason</label>
  <textarea name="rem" class="form-control">
    {{ $atn->w_rem }}
  </textarea>
</form>

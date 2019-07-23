
<div class="container">
    <form id="newpayroll">
        {{ csrf_field() }}
        <input type="hidden" name="mth" value="{{ $mth }}">
        <input type="hidden" name="cm" value="{{ $cm }}">
        <input type="hidden" name="half" value="{{ $half }}">

        <input type="hidden" name="payon" value="{{ date('Ymd', $payon) }}">
        <input type="hidden" name="wagefor" value="{{ date('Ymd', $wagefrom) }}">
        <input type="hidden" name="wageto" value="{{ date('Ymd', $wageto) }}">
        <input type="hidden" name="otfor" value="{{ date('Ymd', $otfrom) }}">
        <input type="hidden" name="otto" value="{{ date('Ymd', $otto) }}">

        <div class="row">
            <label class="col-3">Payroll of Month</label>
            <input type="text" class="col-9 form-control" value="{{ date('F, Y', $payon) }}" readonly>
        </div>
        <div class="row">
            <label class="col-3">Employee Group</label>
            @php($grps = array(553=>'KR',554=>'Of',3186=>'Wf'))
            <input type="text" class="col-9 form-control" value="{{ $grps[$cm] }}">
        </div>
        <div class="row">
            <label class="col-3">Half Period</label>
            <div class="form-group">
                <input type="checkbox" class="form-check-input"{{ ($half ? ' checked' : '') }} > Yes
            </div>
        </div>
        <div class="row">
            <label class="col-3">Date of Payment</label>
            <input type="text" class="col-9 form-control" value="{{ date('D d-M-Y', $payon) }}" readonly>
        </div>

        <div class="row">
            <label class="col-3">Wage Period</label>
            <input type="text" class="col-9 form-control" value="{!! date('d-M-Y', $wagefrom) . ' - ' . date('d-M-Y', $wageto) !!}" >
        </div>
        @if (! $half)
        <div class="row">
            <label class="col-3">OT Period</label>
            <input type="text" class="col-9 form-control" value="{!! date('d-M-Y', $otfrom) . ' - ' . date('d-M-Y', $otto) !!}" >
        </div>
        @endif
    </form>
</div>

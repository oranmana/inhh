<form id="form-employ">
  <table class="table table-condensed">
    <tr>
      <td>
        <label for="id">Data ID</label>
      </td>
      <td>
        <input type="text" class="form-control" id="id" placeholder="Record ID" value="{{ $emp->id }}" readonly>
      </td>
      <td>
        <label for="type">Emp Code</label>
      </td>
      <td>
        <input type="text" class="form-control" id="id" placeholder="Employee code" value="{{ $emp->empcode }}" readonly>
      </td>
      <td>
        <label for="type">Tax Code</label>
      </td>
      <td>
        <input type="text" class="form-control"  placeholder="Tax Code" value="{{ $dir->code }}" readonly>
      </td>
    </tr>
    <tr>
      <td>
          <label for="sex">Gender</label>
      </td>
      <td>
          <select id=sex class="form-control">
            <option value=0 {!! $dir->sex==0?" selected":"" !!}>Mr.</option>
            <option value=1 {!! $dir->sex==1?" selected":"" !!}>Ms.</option>
            <option value=2 {!! $dir->sex==2?" selected":"" !!}>Mrs.</option>
          </select>
      </td>
      <td>
        <label for="name">Full Name</label>
      </td>
      <td colspan=3>
        <input type="text" class="form-control" id="name" placeholder="Enter Full Name" value="{{ $emp->name }}">
      <td>
    </tr>
    <tr>
      <td>
        <label for="name">Nick Name</label>
      </td>
      <td>
        <input type="text" class="form-control" id="nm" placeholder="Enter Nick Name" value="{{ $emp->nm }}">
      </td>
      <td>
        <label for="name">ชื่อเต็ม</label>
      </td>
      <td colspan=3>
        <input type="text" class="form-control" id="thname" placeholder="Enter Full Name in Thai" value="{{ $emp->thname }}">
      </td>
    </tr>
    <tr>
      <td>
        <label for="name">Employed Date</label>
      </td>
      <td>
        <input type="text" class="form-control" id="indate" placeholder="In Date" value="{{ edate($emp->indate) }}">
      </td>
      <td>
        <label for="name">Period</label>
      </td>
      <td>
        <input type="text" class="form-control" value="{{ age($emp->qdate ? $emp->qdate : $emp->indate) }}" readonly>
      </td>
      <td>
        <label for="name">{!! $emp->qcode ? "Last Work" : "Status" !!}</label>
      </td>
      <td>
        <input type="text" class="form-control" value="{!! ($emp->qcode ? edate($emp->qdate) : "Active") !!}">
      </td>
    </tr>
    <tr>
      <td>
        <label for="id">Group</label>
      </td>
      <td>
        <select class="form-control" id="relg">
        @foreach ($cms as $cm)
          <option value="{{ $cm->id }}"{!! ($cm->id == $emp->cm ? " selected" : "") !!}>{{ $cm->ref }}</option>
        @endforeach
        </select>
      </td>
      <td>
        <label for="type">Class</label>
      </td>
      <td>
        <input type="text" class="form-control" id="cls" placeholder="Employee Class" value="{{ $emp->cls }}">
      </td>
      <td>
        <label for="type">Position</label>
      </td>
      <td>
        <select class="form-control" id="post" noedit data="{{$emp->posid}}">
        @foreach ($posts as $pos)
          <option value="{{ $pos->id }}"{!! ($pos->id == $emp->posid ? " selected" : "") !!}>{{ $pos->name }}</option>
        @endforeach
        </select>
      </td>
    </tr>
    <tr>
      <td>
        <label for="id">Organization</label>
      </td>
      <td>
        <select class="form-control" id="org">
        @foreach ($orgs as $org)
          <option value="{{ $org->id }}"{!! ($org->id == $emp->orgid ? " selected" : "") !!}>{!! $org->name . ($org->des ? ' '. $org->des : '') !!}</option>
        @endforeach
        </select>
      </td>
      <td>
        <label for="type">Job Title</label>
      </td>
      <td colspan=3>
        <input type="text" class="form-control" id="job" placeholder="Employee Job Title" value="{!! cm($emp->job) !!}" readonly>
      </td>
    </tr>
  </table>
</form>
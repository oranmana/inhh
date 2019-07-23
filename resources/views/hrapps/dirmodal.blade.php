@php($dir = ($app ? $app->dir : 0) )
<form method="post" id="dirmodalform">
  <input type="hidden" name="_token" value="{!! csrf_token() !!}">
  <input type="hidden" name="rqid" value="{!! $app->rqid !!}">
  <input type="hidden" name="appid" value="{!! $app->id !!}">
  <input type="hidden" name="dirid" value="{{ ($app ? $app->dirid : 0) }}">
  <table class="table table-sm table-borderless">
    <tr>
      <td width=50%>
        <table class="table table-sm table-borderless">
          <tr>
            <th>Tax ID : </th>
            <td>
              <input type="text" id="code" name="code"  class="form-control" value="{{ ($dir ? $dir->code : '') }}"></td>
          </tr>
          <tr>
            <th>Gender </th>
            <td><select name=sex class="form-control">
              <option value=0{!! ($dir && $dir->sex==0 ? " selected" : "") !!}>Mr.</option>
              <option value=1{!! ($dir && $dir->sex==1 ? " selected" : "") !!}>Ms.</option>
              <option value=2{!! ($dir && $dir->sex==2 ? " selected" : "") !!}>Mrs.</option>
            </td>
          </tr>
          <tr>
            <th>Name </th>
            <td><input type=text name=name  class="form-control" value="{{ ($dir ? $dir->name : '') }}"></td>
          </tr>
          <tr>
            <th>ชื่อ</th>
            <td><input type=text name=tname  class="form-control" value="{{ ($dir ? $dir->tname  : '') }}"></td>
          </tr>
          <tr>
            <th>Birthday</th>
            <td><input type=date name=bdate  class="form-control" value="{!! ($dir ? $dir->bdate : 0) !!}"></td>
          </tr>
          <tr>
            <th>Age</th>
            <td><input type=text id="age" class="form-control" value="{!! ($dir ? $dir->age : '') !!}" readonly></td>
          </tr>
          <tr>
            <th>Telephone</th>
            <td><input type=text name=tel class="form-control" value="{{ ($dir ? $dir->tel : '') }}"></td>
          </tr>
          <tr>
            <th>Email</th>
            <td><input type=email name=email class="form-control" value="{{ ($dir ? $dir->email : '') }}"></td>
          </tr>
          <tr>
            <th>Memo</th>
            <td><input type=text name=rem class="form-control" value="{{ ($dir ? $dir->rem : '') }}" rows=3></td>
          </tr>
        </table>
      </td>
      <td>
        <table class="table table-sm table-borderless">
          <tr>
            <td colspan=2 class="table-info">Education</td>
          </tr>
          <tr>
            <th title='Graduated Year'>Year</th>
            <td><input type=text name=tax class="form-control" value="{{ ($dir ? $dir->tax : '') }}"></td>
          </tr>
          <tr>
            <th>Level</th>
            <td>
              <select name=zdir class="form-control">
                <option value=0>-Select Level-</option>
              @foreach ($edlvs as $ed)
                <option value="{{ $ed->id }}" {!! $dir && $dir->zdir == $ed->id ? " selected" : "" !!}>{{ $ed->name }}</option>
              @endforeach
              </select>
            </td>
          </tr>
          <tr>
            <th>Institution</th>
            <td><input type=text name=pic class="form-control" value="{{ ($dir ? $dir->pic : '') }}"></td>
          </tr>
          <tr>
            <th>GPA</th>
            <td><input type=text name=appby class="form-control" value="{{ ($dir ? $dir->appby : '') }}"></td>
          </tr>
          <tr>
            <td colspan=2 class="table-info">Skill Level (1 - 10)</td>
          </tr>
          <tr>
            <th>English</th>
            <td>
              <input type="range" class="custom-range form-control" min="0" max="10" step="1" id="kEnglish" name="reg" value="{{ ($dir ? $dir->reg : 0) }}" >
            </td>
          </tr>
          <tr>
            <th>MS Office</th>
            <td><input type="range" class="custom-range form-control" min="0" max="10" step="1" id="kMS" name="cty" value="{{ ($dir ? $dir->cty : 0) }}" ></td>
          </tr>
          <tr>
            <th>IT Skills</th>
            <td><input type="range" class="custom-range form-control" min="0" max="10" step="1" id="kIT" name="appdate" value="{{ ($dir ? $dir->appdate : 0) }}" ></td>
          </tr>
          <tr>
            <th>Interview On</th>
            <td><input type="datetime-local" class="form-control" id="wdate" name="wdate" value="{!! ($app && isdate($app->wdate) ? date('Y-m-d',strtotime($app->wdate)).'T'.date('H:i',strtotime($app->wdate)) : date('Y-m-d').'T10:00') !!}"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>

<script>
  $('input[type=range]').css('border',0);
  $('#code').on('change', function() {
    finddir($(this).val());
  });
</script>
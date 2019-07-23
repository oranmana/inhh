@extends('layouts.app')

@section('content')
  @php ($probated=$con->grp)
  @php($worker = $con->empcls->cat)
  @php($docnum = ($con->doc ? $con->doc->doccode : '-n/a-') )
  @php($app = $con->app)
  @php($job = $con->job)
  @php ($todate = ($con->todate ? $con->todate : date('Y-m-d',strtotime('119 day', strtotime($con->indate)))  ) )
  @php($error=0)
<div class="container">
<div id="datapart">
  <form class="form-group">
    <table class="table table-sm">
      <tr>
        <th>Type</th>
        <td>{{ $con->grp }}.{!! ($con->grp ? 'Probation Pass' : 'Employment Contract') !!}</td>
      </tr>
      <tr>
        <th>Code</th>
        <td>{{ $con->code }}</td>
      </tr>
      <tr>
        <th>Application No.</th>
        <td>{!! $con->id . ($app ? '/'. $app->rqid . '-' . $app->id : '') !!}</td>
      </tr>
      <tr>
        <th>Approval No.</th>
        <td>{!! $docnum !!}</td>
      </tr>
      <tr>
        <th>Executed On</th>
        <td>{!! date('d-M-Y (D)', strtotime($con->indate)) . ($con->grp ? '' : ' - ' .  date('d-M-Y (D)', strtotime($todate)) ) !!}</td>
      </tr>
      <tr>
        <th>Contract Party</th>
        <td>{{ ($con->empid ? $con->emp->fullname : $con->dir->fullname) }}</td>
      </tr>
      <tr>
        <th>Position</th>
        <td>{{ $con->pos->name . ' (' . $con->cls .')' }}</td>
      </tr>
      <tr>
        <th>Job Title</th>
        <td>{{ ($job ? $con->job->name : '') }}</td>
      </tr>
      <tr>
        <th>Basic Salary</th>
        <td>THB{!! number_format($con->amt,2) !!}/Month</td>
      </tr>
      @if($con->empcls->allowance)
      <tr>
        <th>Class Allowance</th>
        <td>THB{!! number_format($con->empcls->allowance,2) !!}/Month</td>
      </tr>
      @endif
      @if( $con->pos->allowance($con->cls) )
      <tr>
        <th>Position Allowance</th>
        <td>THB{!! number_format($con->pos->allowance($con->cls),2) !!}/Month</td>
      </tr>
      @endif
      <tr>
        <th>Authorized by</th>
        <td>{!! $con->signby->fullname . ' / '.$con->signby->curjob->name !!}</td>
      </tr>
      <tr>
        <th>Witness I</th>
        <td>{!! $con->wit1->fullname . ' / '.$con->wit1->curjob->name !!}</td>
      </tr>
      <tr>
        <th>Witness II</th>
        <td>{!! ($con->Wit2 ? $con->Wit2->fullname . ' / '.$con->Wit2->curjob->name : '') !!}</td>
      </tr>
      @if ($con->empid)
      <tr class="bg-info">
        <th>Employed On</th>
        <td>{!! date('d-M-Y', strtotime($con->emp->indate)) !!}</td>
      </tr>
      @endif
      <tr>
        <th>State</th>
        <td>{{ $con->state }}</td>
      </tr>
    </table>
  </form>
</div>
<div id="thpart" ondblclick="$('#engpart').show();$(this).hide();">
  <div id="thcon">
    <center><a href='/hrcons' class="h2">สัญญา{!! $probated ? 'ผ่าน':'จ้าง' !!}ทดลองงาน เลขที่ {{ $con->code }}</a></center>
    <p>สัญญาฉบับนี้ทำขึ้นระหว่าง <strong>บริษัท ฮันฮวา เคมิคัล (ไทยแลนด์) จำกัด</strong> ซึ่งต่อไปนี้เรียกว่า "นายจ้าง" กระทำการแทนโดย <strong>{{ $con->signby->thfullname }}</strong> {{ $con->signby->curjob->tname }}ภายใต้หนังสืออนุมัติเลขที่ {!! $docnum !!} และพนักงานทดลองงาน ซี่งต่อไปนี้เรียกว่า "ลูกจ้าง" ตามเงื่อนไขดังต่อไปนี้:-
    </p>
    <p>
      <ol>
        <li>นายจ้างตกลงจ้าง <strong>{{ $con->dir->thfullname }}</strong> [{!! ($con->dir->code ? $con->dir->code : "<span class='error bg-danger'>".$error++.'</span>') !!}] เป็นพนักงานทดลองงาน ภายใต้สถานะลูกจ้างดังนี้:-
          <ol>
            <li>ขั้นพนักงาน : {{ $con->cls }}</li>
            <li>ตำแหน่งพนักงาน : {{ $con->pos->tname }}</li>
            <li>ตำแหน่งงาน : {{ $con->job->tname }}</li>
          </ol>
        </li>
        <li>สัญญานี้มีผลระหว่าง วันที่ {{ tdate($con->indate,11) }} {!! ($con->grp ? '' : "จนถึงวันที่ " . tdate($todate,11)) !!}</li>
        <li>วันและเวลาทำงาน
          <ol>
            <li>วันทำงานปกติ : วันจันทร์ถึงวัน{!! $worker ? 'เสาร์' : 'ศุกร์' !!}ยกเว้นวันที่นายจ้างประกาศเป็นวันหยุดนักขัตฤกษ์</li>
            <li>เวลาทำงาน : 
              @if($worker)
              <ul>
                <li>กะ A : 08:30 - 16:30</li>
                <li>กะ B : 16:30 - 00:30</li>
                <li>กะ C : 00:30 - 08:30 ของวันถัดไป</li>
              </ul>
              @else
                08:30 - 17:30 [เวลาพัก : 12:00 - 13:00]
              @endif
            </li>
          </ol>
        </li>
        <li>
        <td>ภายใต้สัญญาฉบับนี้ นายจ้างตกลงจ่ายค่าจ้างแก่ลูกจ้างด้วยวิธีฝากเข้าบัญชีธนาคารของลูกจ้างทุก
          <ul>
            @if($worker)
            <li>วันที่ 15 ของเดือน สำหรับค่าจ้างตั้งแต่วันที่ 24 ของเดือนก่อน จนถึงวันที่ 8 ของเดือนที่จ่าย</li>
            <li>และวันสิ้นเดือน สำหรับค่าจ้างตั้งแต่วันที่ 9 จนถึงวันที่ 23 ของเดือนที่จ่าย
            @else
            <li>วันที่ 21 ของเดือน สำหรับค่าจ้างตั้งแต่วันที่ 1 ถึงวันที่สุดท้ายของเดือนที่จ่ายค่าจ้าง</li>
            <li>เว้นแต่รายได้อื่น ๆ ที่ต้องคำนวนจากเวลาทำงาน จะคำนวนสำหรับวันที่ 16 ของเดือนก่อน จนถึงวันที่ 15 ของเดือนที่จ่ายค่าจ้าง</li>
            @endif
          </ul>
          ลูกจ้างตกลงยอมรับเงื่อนไขการจ่ายค่าจ้างดังกล่าว ตามอัตราจ้างดังนี้ :-
          <ol>
            <li>เงินเดือน - <span>{!! number_format($con->amt,2) !!} บาทต่อเดือน</span></li>
            @if($con->empcls->allowance)
            <li>ค่าขั้นพนักงาน - {{ number_format($con->empcls->allowance,2)}}</li>
            @endif
            @if($con->pos->type || $con->pos->sfx)
            <li>ค่าตำแหน่งพนักงาน
              @if($con->pos->sfx)
              <ul>
                <li>ช่วงทดลองงาน {{number_format($con->pos->sfx,2)}} บาทต่อเดือน</li>
                <li>- เมื่อพ้นทดลองงาน {{number_format($con->pos->gl,2)}} บาทต่อเดือน</li> 
              </ul>
              @else
              - {{number_format($con->pos->type,2)}} บาทต่อเดือน
              @endif
            </li>
            @endif
            <li>ค่าตำแหน่งงาน ({{ $con->job->name }}) - {{number_format($con->job->allowance,2) }} บาทต่อเดือน</li>
          </ol>
      <li>ลูกจ้างสัญญาจะทำงานให้กับนายจ้างตามตำแหน่งที่ระบุไว้ รวมถึงงานที่อาจจะได้รับมอบหมายจากนายจ้างโดยไม่โต้แย้งหรือปฎิเสธ และจะไม่ละทิ้งหน้าที่โดยไม่มีเหตุอันสมควร อันจะถือเป็นการจงใจให้นายจ้างได้รับความเสียหาย</li>

      <li>ลูกจ้างยอมรับกฎระเบียบ และเงื่อนไขปัจจุบันของนายจ้าง รวมถึง กฎระเบียบที่นายจ้างอาจออกประกาศใหม่ในอนาคต</li>
      <li>นายจ้างขอสงวนสิทธิ์ในการยกเลิกสัญญาฉบับนี้โดยไม่ชดเชย หรือแจ้งให้ลูกจ้างทราบล่วงหน้า ตามสิทธิ์ทางกฎหมาย หากพบว่าลูกจ้างไม่มีคุณสมบัติตามที่ได้ให้แก่นายจ้างไว้ในใบสมัครงาน และ/หรือ ในระหว่างการสมัครงาน</li>
    </ol>
  </p>
  <p>
  สัญญานี้ทำขึ้นสองฉบับมีข้อความถูกต้องตรงกัน นายจ้างและลูกจ้างถือไว้ฝ่ายละหนึ่งฉบับ และได้ลงลายมือชื่อไว้เป็นหลักฐาน
  </p>
    <table class="table table-borderless">
			<tr valign=bottom>
        <td width=35% >&nbsp;</td>
        <td width=15%>
				  <span class="sign" data-tag="1">ผู้แทนนายจ้าง</span>
        </td>
			  <td width=35%>&nbsp</td>
        <td>
				  <span class="sign" data-tag="2">ลูกจ้าง</span>
        </td>
      </tr>
      
      <tr valign=top>
        <td  class="signbox">
          <span class="sign" data-tag="1">{!! $con->signby->thfullname !!}</span>
        </td>
        <td>&nbsp;</td>
        <td  class="signbox">
          <span>{!! $con->dir->thfullname!!}</span>
        </td>
        <td>&nbsp;</td>
      </tr>

			<tr valign=bottom>
        <td>&nbsp;</td>
        <td>
				  <span class="sign" data-tag="2">พยาน</span>
        </td>
        <td>&nbsp;</td>
        <td>
				  <span class="sign" data-tag="3">พยาน</span>
        </td>
      </tr>

      <tr valign=top>
        <td class="signbox">
          <span class="sign" data-tag="2">{!! $con->wit1->thfullname !!}</span>
        </td>
        <td>&nbsp;</td>
        <td class="signbox">
          <span class="sign" data-tag="3">{!! $con->wit2->thfullname !!}</span>
        </td>
        <td>&nbsp;</td>
      </tr>
		</table>
  </div>
</div>

<div id="engpart" ondblclick="$('#thpart').show();$(this).hide();">
  <div id="engcon">
    <center class="h1">-TRANSLATION-</center>
    <center><a href='/hrcons' class="h2">{!! ($con->grp ? 'Probation Pass under Employment Contract No.' : 'Employment Contract No.') . $con->code !!}</a></center>
    <p>This contract made between <strong>Hanwha Chemical (Thailand) Co.,Ltd.</strong> hereinafter called &quot;Employer&quot;, represented by <strong>{{ $con->signby->fullname }}</strong>, {{ $con->signby->curjob->name }},under approval reference No. {!! $docnum !!} and the new {!! ($probated ? 'probated' : 'probation' ) !!} employee hereinafter called &quot;Employee&quot; as per following conditions:-</p>
    <p>
      <ol>
        <li>Employer {!! $probated ? ' hereby confirms that' : 'agrees to employ' !!} <strong>{{ $con->dir->fullname }}</strong> [{!! ($con->dir->tax ? $con->dir->taxid : "<span class='error bg-danger'>".$error++.'</span>') !!}] {!! $probated ?' has passed probation and review the employed conditions as:-' :  'as Employee under following status:-' !!}
          <ol>
            <li>Class : {{ $con->cls }}</li>
            <li>Position : {{ $con->pos->name }}</li>
            <li>Job Title : {{ $con->job->name }}</li>
          </ol>
        </li>
        <li>{!! ($probated ? 'Since ' : 'This contract is effected during') !!} {{ edate($con->indate,11) }} {!! ($con->grp ? '' : " until " . edate($todate,11)) !!}</li>
        <li>Work Day and Time
          <ol>
            <li>Weekday : Monday to {!! $worker ? 'Saturday' : 'Friday' !!}, excluding Public Holidays announced by Employer.</li>
            <li>Working Time : 
              @if($worker)
              <ul>
                <li>Shift A : 08:30 - 16:30</li>
                <li>Shift B : 16:30 - 00:30</li>
                <li>Shift C : 00:30 - 08:30 of the next date</li>
              </ul>
              @else
                08:30 - 17:30 [Break : 12:00 - 13:00]
              @endif
            </li>
          </ol>
        </li>
        <li>
        <td>By this contract, employer agrees to pay Employee into Employee's bank account on :-
          <ul>
            @if($worker)
            <li>the 15th date of month for wage between 24th date of prior month and 8th of the month</li>
            <li>and end date of month for wage between 9th and 23rd of the month</li>
            @else
            <li>the 21st date of month for wage between the 1st date and end of the month</li>
            <li>wage related to attendance to be for the 16th date of prior month until 15th date of the month</li>
            @endif
          </ul>
          Employee accepts above condition at the rate of :-
          <ol>
            <li>Basic Salary - <span>THB{!! number_format($con->amt,2) !!} per month</span></li>
            @if($con->empcls->allowance)
            <li>Class Allowance - THB{{ number_format($con->empcls->allowance,2)}} per month</li>
            @endif
            @if($con->pos->type || $con->pos->sfx)
            <li>Position Allowance - 
              @if($con->pos->sfx)
              <ul>
                <li>During probation THB{{number_format($con->pos->sfx,2)}} per month</li>
                <li>- After probation THB{{number_format($con->pos->gl,2)}} per month</li> 
              </ul>
              @else
              THB{{number_format($con->pos->type,2)}} per month
              @endif
            </li>
            @endif
            <li>Job Allowance ({{ $con->job->name }}) - THB{{number_format($con->job->allowance,2) }} per month</li>
          </ol>
      <li>Employee promised to work as aforementioned job title including any assignment given by Employer without arguing or deny. Employee shall not abandon the job and assignment which shall be considered to cause damage to Employer intentionally.</li>

      <li>Employee accepts current regulation and rules and any condition issued by Employer together with any regulation and rules which Employer may announce in the future.</li>
      <li>Employer reserves the right to terminate this contract if found that Employee is not qualified as given during recruitment without any compensation or any prior notice by law.</li>
    </ol>
  </p>
  <p>
  This contract is executed in duplicate, with each party holding one copy.  Both copies shall come into force upon signature of both parties and have the same effect.
  </p>
    <table class="table table-borderless">
			<tr valign=bottom>
        <td  class="signbox">
          <span >{!! $con->signby->fullname !!}</span>
        </td>
        <td><span >Employer</span></td>
        <td  class="signbox">
          <span>{!! $con->dir->fullname!!}</span>
        </td>
        <td><span >Employee</span></td>
      </tr>
			<tr>
        <td  class="signbox">
          <span >{!! $con->wit1->fullname !!}</span>
        </td>
        <td><span >Witness</span></td>
        <td  class="signbox">
          <span>{!! $con->wit2->fullname!!}</span>
        </td>
        <td><span >Witness</span></td>
      </tr>
		</table>
  </div>
</div>
<div id='#foot' class="row text-center">
  <div class="col"><button class="btn" id="printout">Print</button></div>
  @if($con->empid==0)
    <div class="col">
      <form method="POST" action="emp/create">
      {!! csrf_field() !!}
      <input type="hidden" name="conid" value="{{ $con->id }}">
      <button class="btn btn-primary" role="submit" id="createemp">Create Employee Profile</button>
      </form>
    </div>
    <div class="col">
      <form method="POST" action="contract/delete">
      {!! csrf_field() !!}
      <input type="hidden" name="conid" value="{{ $con->id }}">
      <button class="btn btn-danger" role="submit" id="delcontract">Delete Contract</button>
      </form>
    </div>
  @endif
</div>
</div>
@endsection
@section('script')
<script>
  $(document).ready(function() {
    $('.signbox')
      .addClass('text-center')
      .addClass('border-top');
    $('#engpart').hide();
    $('#datapart').hide();
  });
</script>
@endsection
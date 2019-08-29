<?php

namespace App\Http\Controllers\files;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CommonFile extends Controller
{
  public function xcommons() {
      $target = 'commons';
      $sql = "select c_id as id,
        c_parent as par,
        c_main as main,
        c_num as num,
        c_code as code,
        c_name as name,
        c_tname as tname,
        c_des as des,
        c_sub as sub,
        c_type as 'type',
        c_group as 'group',
        c_cat as cat,
        c_ref as ref,
        c_off as off,
        c_dir as dir,
        com_gl as gl,
        com_sfx as sfx,
        c_node as node,
        c_pj as pj,
        c_erp as erp
      from hctdb.tbcommon
      order by c_id;";
      return $this->ximporttable($target, $sql);
  }

  public function xhctcal() {
    $target = 'hctcal';
    $sql = "select cl_id as id, cl_date as cldate, cl_holiday as holiday, cl_des as rem,
      if(date_format(cl_date,'%w') in (0,6), 3683, 3678) as of,
      at_ofw as ofm, at_ofa as ofo,
      if(date_format(cl_date,'%w') in (0), 3683, 3679) as wf,
      at_wfw as wfm, at_wfa as wfo, 1 as state,
      5 as CREATED_BY, now() as CREATED_AT, 5 as UPDATED_BY, now() as UPDATED_AT
    from hctdb.hr_cal
    order by cl_date;";
    return $this->ximporttable($target, $sql);
  }

  // prototype
  public function xcountry() {
    $target = 'countries';

    $sql = " select c_id as id, c_parent as par, c_num as num, c_sub as sub,
        c_code as code, c_name as name, c_tname as tname,
        c_ref as ref, c_des as des, c_group as `group`, c_off as off,
        5 as CREATED_BY, NOW() AS CREATED_AT,
        5 as UPDATED_BY, NOW() AS UPDATED_AT
      from hctdb.tbcountry
      where not (ifnull(c_name,'') = '' and ifnull(c_code,'') = '')
      order by c_id;";
    return $this->ximporttable($target, $sql);
  }    

  /////////////////////////////////////////
  public function xdoc() {
    $target = 'docs';
    $sql = "select *, 5 as UPDATED_BY, NOW() as UPDATED_AT from (
      select
        0 as id,
        pp_gp as typeid,
        pp_code as code,
        concat('PP-', lpad(year(pp_date)-2000,2,'0'),'-', lpad(pp_code,4,'0')) as doccode,
        pp_date as indate,
        pp_tm as tmid,
        pp_subj as name,
        pp_rem as rem,
        pp_budget as amt,
        pp_hide as hide,
        emp_name as docfrom,
        'President' as docto,
        0 as ref,
        status as state,
        pp_by as created_by,
        pp_date as created_at,
        1 as pp,
        pp_id as oid
      from hctdb.hct_pp
        left join hctdb.tbcommon on c_id = pp_tm
        left join hctdb.tbuser
          left join hctdb.tbemployee on emp_id = usr_emp
        on usr_id = pp_by
      union
      select
        0 as id,
        cid as typeid,
        log_code as code,
        concat(if(tp, cref, org.c_code),'-', lpad(year(log_date)-2000,2,'0'),'-', lpad(log_code,4,'0')) as doccode,
        log_date as indate,
        log_tm as tmid,
        log_subj as name,
        log_des as rem,
        0 as amt,
        0 as hide,
        log_from as docfrom,
        log_to as docto,
        log_pp as ref,
        5 as state,
        log_by as created_by,
        log_tstamp as created_at,
        0 as pp,
        log_id as oid
      from hctdb.tblog
        left join hctdb.tbcommon as org on org.c_id = log_tm
        left join (
          SELECT c_id as cid, c_code as ccode, c_type as tp, c_sub, c_ref as cref
          FROM hctdb.tbcommon WHERE c_parent=5078
        ) as doctype on ccode = log_doc
      where log_tm > 0
      ) as a
      order by indate, doccode;";

      return $this->ximporttable($target, $sql);
  }
/////////////////////////////////////////
  public function xasset() {
    $target = 'assets';

    $sql = "select ast_id as id,
      erp_item as itemid,
      ast_des as des,
      ast_brand as brand,
      ast_model as model,
      ast_serial as `serial`,
      ast_datein as indate,
      ast_doc as indoc,
      ast_val as amount,
      ast_dateout as dateout,
      ast_status as state,
      ast_loc as locationid,
      ast_par as par,
      erp_class as sapitemid,
      ast_erp as sapcode,
      ast_code as oldsap,
      ast_pic as picid,
      ast_tm as teamid,
      0 as CREATED_BY,
      now() as CREATED_AT,
      0 as UPDATED_BY,
      lastupdate as UPDATED_AT
    from hctdb.tbasset
    order by ast_id;";

    return $this->ximporttable($target, $sql);
  }    

  public function xassets() {
    $target = 'assetlogs';

    $sql = "select ast_id as id, ast_id*1 as assetid, ast_datein as actiondate, ast_doc as actiondoc,
      ast_status as oldstate, ast_loc as oldlocationid, ast_pic as oldpicid, ast_tm as oldteamid,
      ast_status as newstate, ast_loc as newlocationid, ast_pic as newpicid, ast_tm as newteamid,
      5 as CREATED_BY, now() as CREATED_AT,
      5 as UPDATED_BY, now() as UPDATED_AT
      from hctdb.tbasset
      order by ast_id;";
    return $this->ximporttable($target, $sql);
  }    
/////////////////////////////////////////
  public function xemps() {
    $target = 'emps';
    $sql = "select emp_id as id,
        emp_dir as dirid,
        emp_sid as empcode,
        emp_cm as cm,
        emp_nm as nm,
        emp_name as name,
        emp_tname as thname,
        emp_date as indate,
        emp_retire as retireage,
        emp_retired as xdate,
        emp_q as qcode,
        emp_qdate as qdate,
      
        emp_bplace as bplace,
        emp_relg as relg,
        emp_raddr as address,
        emp_house as house,
        emp_tel as tel,
        emp_mtel as mobile,
        emp_edu as edu,
        emp_car as car,
        emp_blood as blood,
        emp_wg as weight,
        emp_hg as height,
        emp_military as military,
      
        emp_cls as cls,
        emp_org as orgid,
        emp_cm_pos as posid,
        emp_job as jobid,
        erp.c_erp as ccid,
      
        emp_fdate as pvdate,
        emp_fcode as pvcode,
        pay_fund as pvcom,
        emp_fund as pvemp,
        emp_fedate as pvxdate,
      
        pay_amt as pwage,
        pay_pos as ppost,
        pay_food as pfood,
        pay_house as phouse,
        if(emp_cm=553,0,pay_live) as pcls,
        if(emp_cm=553,pay_live,0) as plive,
        pay_trans as pfuel,
        pay_tuition as pedu,
        if(emp_id in (477,638,769), 0, pay_oth) as pdg,
        if(emp_id in (477,638,769), pay_oth,0) as pmove,
      
      
        emp_code as taxcode,
        emp_sex as sex,
        emp_nation as nation,
        emp_bdate as bdate,
        emp_addr as haddr,
        emp_card as cardno,

        0 as CREATED_BY,
        now() as CREATED_AT,
        0 as UPDATED_BY,
        now() as UPDATED_AT
      
      from hctdb.tbemployee
        left join hctdb.tbcommon as erp on erp.c_id = emp_erp;";
        return $this->ximporttable($target, $sql);
  }

  public function xempdat() {
    $source = 'hr_emp';
    $target = 'empsdata';
    $temp = 'test_'.$target;
    DB::statement('DROP TABLE IF EXISTS ' . $temp);
    DB::statement('CREATE TABLE ' . $temp . ' like '. $target);
    $sqls = ["select ed_id as id,
        1 as grp,
        ed_emp as empid,
        ed_level as code,
        ed_inst as ref,
        ed_year as yr,
        ed_instt as name,
        ed_rem as rem,
        ed_place as loc,
        ed_grd as grd,
        1 as state,
        5 as CREATED_BY,
        now() as CREATED_AT,
        5 as UPDATED_BY,
        now() as UPDATED_AT,
        ed_id as oid
      from hctdb.hr_emp_edu
      where ed_level
      order by ed_id;",
      "select 2 as grp, trs_emp as empid, trs_tr as ref, trs_hr as grd, ifnull(date(tr_date),0) as yr, now() as CREATED_AT, trs_fee as name, trs_exp as rem, trs_lv as loc, trs_id as oid, 
      5 as CREATED_BY, 5 AS UPDATED_BY, NOW() AS UPDATED_AT
      from hctdb.hr_emp_train 
        left join hctdb.hr_train on tr_id = trs_tr;",
      "select 3 as grp, j_emp as empid, j_job as ref, ifnull(date(j_date),0) as yr, now() as CREATED_AT, j_status as state, j_id as oid,
        5 as CREATED_BY, 5 AS UPDATED_BY, NOW() AS UPDATED_AT
      from hctdb.hr_emp_job"];
    $ln = 1;
    foreach($sqls as $sql) {
      $sourcedata = DB::connection('mysql2')->select(DB::raw($sql));
      foreach($sourcedata as $src) {
        $ln++;
        $data =   @json_decode(json_encode($src), true);
        DB::table($temp)->insert( $data );
      }
    }
    return $source . '->' . $target . '<br>'. 'insert ' . $target . ' ' . $ln . ' Records inserted';
  }

  public function xemppromote() {
    $target = 'EmpPromotions';
    $sql = "select
        0 as id,
        0 as docid,
        em_emp as empid,
        em_date as indate,
        em_xdate as xdate,
        em_on as `on`,
        em_cls as cls,
        em_pos as posid,
        em_org as orgid,
        em_job as jobid,
        null as rem,
        em_id*1 as oid,
        1 AS CREATED_BY,
        concat(em_date,' ','08:30:00') as CREATED_AT,
        1 AS UPDATED_BY,
        concat(em_date,' ','08:30:00') as UPDATED_AT
      from hctdb.tbemployees
      where em_emp < 10000
      order by em_date, em_emp;";

    return $this->ximporttable($target, $sql);
  }

  public function xempbenefit() {
    $target = 'EmpPayItems';
    $sql = "select
      em_id as id,
      0 as docid,
      em_emp as empid,
      em_date as indate,
      ifnull(em_xdate,ifnull(emp_qdate, null)) as xdate,
      if(emp_qdate is null, em_on, if(emp_qdate is null,em_on,0)) as `on`,
      em_pay as pay,
      py_wage as wage,
      if(emp_cm>553,py_live,0) as cls,
      if(emp_cm<3186,py_pos,0) as pos,
      if(emp_cm<3186,0,py_pos) as job,
    
      if(emp_cm>553,0,py_live) as live,
      py_edu as edu,
    
      py_trans as trans,
      py_house as house,
      py_food as food,
    
      if(em_rem='DG',py_oth, 0) as prof,
      if(em_rem='Mobile',py_oth, 0) as comm,
      if(em_rem='Off.Move',py_oth, 0) as omove,
      em_rem as rem,
      em_id*1 as oid,
      1 AS CREATED_BY,
      concat(em_date,' ','08:30:00') as CREATED_AT,
      1 AS UPDATED_BY,
      concat(em_date,' ','08:30:00') as UPDATED_AT
    from hctdb.tbemployees
      left join hctdb.tbemployee on em_emp = emp_id
    where py_wage > 0;";

    return $this->ximporttable($target, $sql);
  }
/////////////////////////////////////////
public function xhrrequest() {
  $target = 'hrrequest';
  $sql = "select rq_id as id,
    date_format(rq_time,'%Y-%m-%d') as indate,
    rq_date as rqdate,
    rq_job as jobid,
    docs.id as docid,
    if(rq_gp=1,rq_ref,0) as dcid,
    if(rq_gp=2,rq_ref,0) as ppid,
    rq_des as des,
    rq_state as state,
    rq_contract as rcid,
    rq_by as CREATED_BY,
    rq_time as CREATED_AT,
    rq_by as updATED_BY,
    now() as UPDATE_AT
  from hctdb.hr_request
    left join docs on oid=rq_ref and docs.pp = if(rq_gp=1,0,1)
  order by rq_id;";

  return $this->ximporttable($target, $sql);
}

public function xhrapplicants() {
  $target = 'hrapps';
  $sql = "select ap_id as id,
    ap_rq as rqid,
    ap_dir as dirid,
    ap_idate as wdate,
    ap_mem as rem,
    0 as amt,
    null as indate,
    0 as score,
    ap_state as state,
    docs.id as docid,
    ap_rc as rcid,
    ap_pp as ppid,
    ap_log as dcid,
    ap_by as CREATED_BY,
    ap_time as CREATED_AT,
    ap_by as UPDATED_BY,
    ap_time as UPDATED_AT
  from hctdb.hr_apply
    left join docs on if(ap_pp, pp=1 and ap_pp=oid, pp=0 and ap_log = oid)
  order by ap_id";

  return $this->ximporttable($target, $sql);
}

public function xhrcontract() {
  $target = 'hrContracts';
  $sql = "select rc_id as id,
    rc_gp as grp,
    rc_code as code,
    rc_app as appid,
    if (pp_id, 1, 0) as ppid,
    if (pp_id, pp_id, log_id) as docid,
    rc_dir as dirid,
    rc_emp as empid,
    rc_job as jobid,
    rc_date as indate,
    rc_till as todate,
    rc_cls as cls,
    rc_pos as posid,
    rc_pay as amt,
    0 as clsamt,
    0 as posamt,
    0 as jobamt,
    rc_sign as empsign,
    rc_wit1 as empwit1,
    rc_wit2 as empwit2,
    rc_state as state,
    rc_by as CREATED_BY,
    rc_at as CREATED_AT,
    0 as UPDATED_BY,
    now() as UPDATED_AT
  from hctdb.hr_recruit
    left join hctdb.hct_pp on pp_id = rc_ref
    left join hctdb.tblog on log_id = rc_ref
  order by rc_id";

  return $this->ximporttable($target, $sql);
}

/////////////////////////////////////////
  // hr control

  public function xattn() {
    $target = 'hrworks';
    $sql = "select 0 as w_id, at_emp as w_empid, em_id as w_empsid,
        at_date as w_date, at_wk as workid,
        at_ot1 as w_ot1, at_ot2 as w_ot2, at_ot3 as w_ot3,
        at_lv as w_lvid, at_rem as w_rem, at_status as w_state,
        at_tin as tin, at_tout as tout, at_ftime as tin2, at_stime as tout2,
        if(at_date > 20150630, 217, if(at_date > 20100531, 141, 5)) as CREATED_BY, now() as CREATED_AT,
        if(at_date > 20150630, 217, if(at_date > 20100531, 141, 5)) as UPDATED_BY, now() as UPDATED_AT, at_id
      from hctdb.hr_attn
        left join hctdb.tbemployees
          left join hctdb.tbemployee on em_emp = emp_id
        on at_emp = em_emp and at_date between em_date and ifnull(em_xdate, if(emp_qdate is null, 20191231, emp_qdate))
      order by at_id;";
      return $this->ximporttable($target, $sql);
  }

  public function xleaverequest() {
    $target = 'hrlvrqs';
    $sql = "select 0 as id, lv_emp as empid, lv_type lvid, lv_fdate as fdate, lv_day as num, 
        lv_rem as rem, lv_ref as trref, 
        lv_status as state,
        lv_by as requested_by, lv_at as requested_at,
        if(verified_by>0, verified_by, 0) as verified_by, verified_at,
        if(approved_by>0, approved_by, 0) as approved_by,  approved_at,
        now() as UPDATED_AT, lv_id
      from hctdb.hr_leave
        left join (
          select id as sgid,
            if(state=1, userid, 0) as request_by, if(state=1, timeat, null) as request_at,
            if(state=2, userid, 0) as verified_by, if(state=2, timeat, null) as verified_at,
            if(state=3, userid, 0) as approved_by, if(state=3, timeat, null) as approved_at
          from (
            select sg_rec as id, sg_mode as state, sg_by as userid, sg_at as timeat
            from hctdb.tbsign
            where sg_file=484
        ) as a group by sgid
      ) as a on sgid = lv_id
      order by lv_at, lv_id;";
    return $this->ximporttable($target, $sql);
  }

  public function xhrtimes() {
    $target = "hrtimes";
    $sql = "select 0 as t_id, tr_emp as t_empcode, tm_id as t_empid, 
      tr_date as t_tdate, tr_trec as t_time, trim(tr_raw) as t_raw,
      5 as CREATED_BY, now() as CREATED_AT,
      5 AS UPDATED_BY, NOW() AS UPDATED_AT
    from hctdb.hr_time
    where tr_date >= 20180101
    order by tr_trec,tr_id;";

    return $this->ximporttable($target, $sql);
  }

  public function xattns() {
    $target = 'hrattns';
    $sql = "select b.w_id, if(wk=0,nwk,wk) as wkid, hl, w1, w2, 
        l1 as lm11, l2 as lm12, l3 as lm21, l4 as lm22,
        t1 as lh1, t2 as lh2,
        o1 as oh1, o2 as oh2, o3 as oh3,
        lvc as lvid, lv as lvd, lvhr as lvh,
        ot10 as oth10, ot15 as oth15, ot20 as oth20, ot30 as oth30, 
        lvamt, otamt, ltamt,
        if(at_date > 20150630, 217, if(at_date > 20100531, 141, 5)) as CREATED_BY, now() as CREATED_AT,
        if(at_date > 20150630, 217, if(at_date > 20100531, 141, 5)) as UPDATED_BY, now() as UPDATED_AT, atid as at_id
      from hctdb.hr_attns as a
        left join hrworks as b on atid = at_id
      order by atid desc;  ";
    return $this->ximporttable($target, $sql);
  }

  public function xtrain() {
    $target = 'training';
    $sql = "select tr_id as id, upper(tr_code) as code, tr_cm as category_id, (tr_in=1) as inside,
      tr_date as ondate, tr_tdate as todate, tr_day as hours,
      0 as organize_id, tr_course as coursename, tr_loc as place, tr_rem as remark,
      tr_fee as amt_fees, tr_exp as amt_expenses, tr_status as state,
      if (tr_date > 20171001, 238, 5) as CREATED_BY, now() AS CREATED_AT,
      if (tr_date > 20171001, 238, 5) as UPDATED_BY, now() as UPDATED_AT
    from hctdb.hr_train
    order by tr_id;";
    return $this->ximporttable($target, $sql);
  }
  
  public function xtrains() {
    $target = 'trainings';
    $sql = "select trs_id as id, trs_tr as train_id, trs_emp as emp_id,
      trs_hr as trainhours, trs_date as traindate,
      trs_fee as fees, trs_exp as expenses,
      trs_lv as leaverq_id,
      5 as CREATED_BY, now() as CREATED_AT,
      5 as UPDATED_BY, now() as UPDATED_AT
    from hctdb.hr_emp_train
    order by trs_id;";
    return $this->ximporttable($target, $sql);
  }

//payroll///////////////////////////////////////
  public function xpayroll() {
    $target = 'payrolls';
    $sql = "select 0 as id, left(mp_code,6) as mth, right(mp_code,2)*1 as num,
      if(mp_ecm>0, mp_ecm, elt(mp_cm,553,554,3186,9637) )*1 as grp_id,
      ifnull(fwdate,wagefroms) as wagefor,
      if(day(mp_pdate) >=14 and day(mp_pdate) <=16, date_sub(ifnull(twdate, wagetos),interval 15 day), ifnull(twdate, wagetos)) as wageto,
      if(day(mp_pdate) >=14 and day(mp_pdate) <=16,1,0) as wageonly,
      if(day(mp_pdate) >=14 and day(mp_pdate) <=16,null,ifnull(fodate,otfroms)) as otfor,
      if(day(mp_pdate) >=14 and day(mp_pdate) <=16,null,ifnull(todate,ottos)) as otto,
      mp_pdate as payon, mp_state as state,
      mp_pmt as erp_payby, mp_vendor as erp_vendor, mp_amt as erp_payroll,
      mp_by as CREATED_BY, mp_date as CREATED_AT,
      mp_by as UPDATED_BY, now() as UPDATED_AT,
      mp_id
    from hctdb.hr_emp_pay
      left join (
    select mth, wf, max(wagefrom) as wagefroms, max(wageto) as wagetos, max(otfrom) as otfroms, max(otto) as ottos
    from (
    select at_ofw as mth, 0 as wf, min(cl_date) as wagefrom, max(cl_date) as wageto, null as otfrom, null as otto
    from hctdb.hr_cal
    where at_ofw is not null
    group by 1
    union
    select at_wfw as mth, 1 as wf, min(cl_date) as wagefrom, max(cl_date) as wageto, null as otfrom, null as otto
    from hctdb.hr_cal
    where at_wfw is not null
    group by 1
    union
    select at_ofa as mth, 0 as wf, null as wagefrom, null as wageto, min(cl_date) as otfrom, max(cl_date) as otto
    from hctdb.hr_cal
    where at_ofa is not null
    group by 1
    union
    select at_wfa as mth, 1 as wf, null as wagefrom, null as wageto, min(cl_date) as otfrom, max(cl_date) as otto
    from hctdb.hr_cal
    where at_wfa is not null
    group by 1) as a
    group by mth,wf
      ) as cal on left(mp_code,6) = mth and wf = if(mp_cm > 2,1,0)
    order by mp_code;";

    return $this->ximporttable($target, $sql);
  }

  public function xpayrollemp() {
    $target = 'payrollemps';
    $sql = "select 0 as id, pr.id as payroll_id, p_emp as emp_id, p_pay as rate_id, p_org as org_id, p_pos as pos_id,
      if (intel > 0, 1, 0) as fullwork,
      cm.c_erp as erp_cc, p_ba as erp_ba,
      mp_by as CREATED_BY, mp_date as CREATED_AT,
      mp_by as UPDATED_BY, now() as UPDATED_AT,
      p_id
    from hctdb.hr_emps_pay as mp
      left join hctdb.hr_emp_pay as emppay on emppay.mp_id = p_mth
      left join payrolls as pr on  pr.mp_id = p_mth
      left join hctdb.tbcommon as cm on cm.c_id = p_cc
      left join (select ps_p, sum(ps_amt) as intel from hctdb.hr_emps_pays where ps_exp = 3798 group by ps_p) as ps on ps_p = p_id
    order by p_id;";

    return $this->ximporttable($target, $sql);
  }

  public function xpayrollamt() {
    $target = 'payrollamts';
    $sql = "insert into " . $temp .
    " select 0 as id, b.id as payemp_id, (ps_dc > 0) as plus, a.ps_exp as item_id, a.ps_amt as amount,
      a.ps_rem as remark, exp.c_erp as erp_gl,
      d.mp_by as CREATED_BY, d.mp_date as CREATED_AT,
      d.mp_by as UPDATED_BY, now() as UPDATED_AT,
      ps_id
    from hctdb.hr_emps_pays as a
      left join hctdb.tbcommon as exp on exp.c_id = a.ps_exp
      left join hctdb.hr_emps_pay as c
        left join hctdb.hr_emp_pay as d on d.mp_id = c.p_mth
      on a.ps_p = c.p_id
      left join payrollemps as b on a.ps_p = b.p_id
    order by a.ps_id;";

    return $this->ximporttable($target, $sql);
  }

/////////////////////////////////////////
  public function xsapitem() {
    $target = 'sap_item';
    $sql = "SELECT
      `itm_id`, `itm_code`, `itm_name`, `itm_ba`,`itm_type`,`itm_sub`,`itm_grp`,`itm_plant`,`itm_vc`,
      `itm_dcl`,`itm_coa`,`itm_pj`,`itm_p1`,`itm_p2`,`itm_p3`,`itm_pk`,`itm_uom`,`i_uom`,`itm_cname`,`itm_wdm`,`itm_kg`,
      `FacGroup`,`itm_prc`,`itm_c3`,`itm_c6`,
      NOW() as CREATED_AT, NOW() AS UPDATED_AT
    FROM hctdb.sap_item
    ORDER BY itm_id;";

    return $this->ximporttable($target, $sql);
  }

  public function xmkpoint() {
    $target = 'mkpoint';
    $sql = "select
      px_id as id,
      px_dc as dom,
      px_code as code,
      px_dir as dirid,
      px_port as portid,
      px_city as cityid,
      px_zone as zoneid,
      pf_prterm as pricetermid,
      pf_pyterm as paymentid,
      pz_gp,
      pz_usr as CREATED_BY,
      lastc as CREATED_AT,
      pz_usr as UPDATED_BY,
      lastc as UPDATED_AT,
      px_pt
    from hctdb.mk_point
    order by px_id;";
    return $this->ximporttable($target, $sql);
  }

  public function xmkcredit() {
    $target = 'mkcredit';
    $sql = "select
    c_id as id,
    max(if(inv_id is null, c_dir, inv_dir)) as customerid,
    c_dir as dirid,
    c_mode as typeid,
    c_code as code,
    c_date as opendate,
    sum(if(inv_id is null, 0, inv_amt)) as amount,
    c_benf as beneficiary,
    c_app as applicant,
    c_cons as consignee,
    c_ntf as notify,
    c_des as description,
    c_rem as remark,
    c_mark as shippingmark,
    c_bln as blnum,
    c_bank as bank,
    c_drwn as drawing,
    c_drwe as drawee,
    c_day as creditdays,
    c_pfrom as fromport,
    c_pto as toport,
    c_pdvry as placeofdelivery,
    c_bcer as certbeneficiary,
    c_coa as certcoa,
    if(c_date < date_sub(now(), interval 1 year), 1, 0) as state,
    0 as CREATED_BY,
    NOW() AS CREATED_AT,
    0 as UPDATED_BY,
    NOW() AS UPDATED_BY
  from hctdb.mk_crd
    left join hctdb.mk_inv on inv_crd = c_id
  where (inv_id is not null or c_date > date_sub(now(), interval 1 year)) and c_mode > 0
  group by c_id
  order by c_id;";

    return $this->ximporttable($target, $sql);
  }

  public function xmkbook() {
    $target = 'mkbooking';
    $sql = "select
      bk_id as id,
      bk_date as bookdate,
      bk_code as code,
      bk_agent as agentid,
      bk_pagent as agentpersonid,
      bk_liner as linerid,
      bk_lcl as qlcl,
      bk_20 as q20,
      bk_20h as q20h,
      bk_40 as q40,
      bk_40h as q40h,
      bk_vf as feederid,
      bk_yf as feedervoy,
      bk_etd as etddate,
      bk_vs as carrierid,
      bk_ys as carriervoy,
      bk_eta as etadate,
      bk_fport as portfromid,
      bk_tport as porttoid,
      
      bk_fdate as receivedate,
      bk_fdepo as receivedepoid,
      bk_fperson as receivepersonid,
      bk_frem as receivememo,
      bk_xfrom as receivefrom,
      bk_xfcontact as receiveperson,
      
      bk_tdate as returndate,
      bk_tdepo as returndepoid,
      bk_tperson as returntopersonid,
      bk_trem as returnmemo,
      bk_xto as returnto,
      bk_xtcontact as returnperson,
      
      bk_close as closetime,
      
      bk_frt as freightprice,
      bk_lcl as lclprice,
      bk_f20 as f20price,
      bk_f40 as f40price,
      bk_th20 as thc20price,
      bk_th40 as thc40price,
      bk_doc as docprice,
      
      bk_rem as remark,
      bk_status as state,
      
      
      bk_by as CREATED_BY,
      bk_date as CREATED_AT,
      bk_by as UPDATED_BY,
      bk_by as UPDATED_AT
    
    from hctdb.mk_book
    where bk_code is not null or bk_agent > 0
    order by bk_id";

    return $this->ximporttable($target, $sql);
  }

  public function xmkvessel() {
    $target = 'mkvessel';
    $sql = "select
      vsl_id as id,
      vsl_name as name,
      0 as CREATED_BY,
      now() as CREATED_AT,
      0 as UPDATED_BY,
      now() as UPDATED_AT
    from hctdb.tbvessel
    order by vsl_id;";

    return $this->ximporttable($target, $sql);
  }

  public function xmkinvoice() {
    $target = 'mkinvoices';
      $sql = "select inv_id as id,
      version as version,
      inv_crd as creditid,
      inv_pic as picempid,
      inv_pj as pj,
      inv_dom as dom,
      inv_num as invnum,
      inv_so as sonum,
      inv_ref as ponum,
      inv_bl as blnum,
      
      inv_date as invdate,
      inv_ldate as loaddate,
      inv_bdate as bldate,
      
      inv_dir as dirid,
      inv_usd as currencyid,
      inv_sc as paytermid,
      inv_term as pricetermid,
      inv_pt as pointid,
      inv_book as bookid,
      
      inv_amt as amt,
      inv_fob as fobamt,
      inv_frt as freightamt,
      inv_ins as insureamt,
      
      inv_brk as brokerid,
      inv_brkd as brokerdate,
      
      inv_lcl as qlcl,
      inv_20 as q20,
      inv_20h as q20h,
      inv_40 as q40,
      inv_40h as q40h,
      
      inv_qty as quantity,
      inv_cnet as netweight,
      inv_cgross as grossweight,
      
      inv_bill as billnum,
      inv_mem as memo,
      inv_status as state,
      
      crby as CREATED_BY,
      crat as CREATED_AT,
      crby as UPDATED_BY,
      crat AS UPDATED_AT
      
    from hctdb.mk_inv
    where inv_bdate is not null
    order by inv_bdate, inv_num, inv_id;";

    return $this->ximporttable($target, $sql);
  }

  public function xmkcustoms() {
    $target = 'mkcustoms';
    $sql = "select 0 as id,
      inv_id as invid,
      inv_cus as exporttype,
      inv_ctax as refundcode,
      inv_cusport as exportportid,
      inv_cusnum as exportnum,
      inv_custxd as entrydate,
      inv_cuschk as date03,
      inv_cusdate as date04,
      inv_exc as exportrate,
      inv_exctr as paymentrate,
      inv_ctax as taxrefundcode,
      crby as CREATED_BY,
      crat as CREATED_AT,
      0 as UPDATED_BY,
      0 AS UPDATED_AT
    from hctdb.mk_inv
    where inv_cus > 0
    order by inv_bdate, inv_num, inv_id;";

    return $this->ximporttable($target, $sql);
  }

  public function xmkvat() {
    $target = 'mkvat';
    $sql = "select 0 as id,
      inv_id as invid,
      inv_bdate as invdate,
      inv_dir as customerid,
      inv_grp as grp,
      inv_cat as cat,
      inv_thb as thb,
      inv_vat as vat,
      tmp_dn as dnid,
      inv_rcp as receiptid,
      inv_rem as remark,
      crby as CREATED_BY,
      crat as CREATED_AT,
      0 as UPDATED_BY,
      0 AS UPDATED_AT
    from hctdb.mk_inv
    where inv_dom = 3
    order by inv_bdate;";

    return $this->ximporttable($target, $sql);
  }

  public function xmkpacklist() {
    $target = 'mkpackinglist';
    $sql = "select
      p_id as id,
      p_inv as invid,
      p_pd as sapitemid,
      p_uom as sizeid,
      p_q as packquantity,
      p_qty as salequantity,
      p_load as loadquantity,
      p_upr as unitprice,
      p_qty* p_upr as amount,
      p_frt as freightamount,
      p_ins as insureamount,
      p_net as netkgs,
      p_gross as grosskgs,
      p_sqm as sqm,
      
      p_cus as refunditem,
      
      0 AS CREATED_BY,
      NOW() AS CREATED_AT,
      0 AS UPDATED_BY,
      NOW() AS UPDATED_AT
      
      p_itm as xp_itm,
      p_pj as xp_pj,
      p_amt as xp_amt,
      
    from hctdb.mk_pck
    where p_inv > 0
    order by p_id;";

    return $this->ximporttable($target, $sql);
  }

  public function xmkload() {
    $target = 'mkloadbox';
    $sql = "select
      ld_id as id,
      invid as invid,
      con_num as connum,
      con_seal as sealnum,
      con_sz as sizeid,
      ld_tin as datein,
      ld_date as dateload,
      ld_tout as dateout,
      cn_tare as tarekg,
      ld_mem as remark,
      1 AS CREATED_BY,
      now() AS CREATED_AT,
      1 AS UPDATED_BY,
      now() AS UPDATED_AT
    from hctdb.wh_ld
      left join hctdb.trcon
        left join hctdb.trconc on cn_con = cc_id
      on cc_name = con_num
      left join hctdb.mk_inv on inv_id = invid
    where inv_id > 0;";

    return $this->ximporttable($target, $sql);
  }    
  
  public function xmkloaditem() {
    $target = 'mkloaditem';
    $sql = "select os_id as id, os_ld as boxid, os_pd as packid, os_roll as quantity, os_rem as rem,
      0 as CREATED_BY, NOW() AS CREATED_AT,
      0 as UPDATED_BY, NOW() AS UPDATED_AT
    from wh_os as a
      left join wh_ld
        left join mk_inv on inv_id = inv_id
      on os_ld = ld_id
    where inv_id > 0;";

    return $this->ximporttable($target, $sql);
  }

////////////////////////////////Engine////////////////////////////
  public function ximporttable($target, $sql, $end=0) {
    $backup = $target . '_' . date('Ymd');
    DB::statement("ALTER TABLE " . $target ." RENAME TO " . $backup . ";");
    DB::statement("CREATE TABLE " . $target . " like " . $backup . ";");
    DB::statement("INSERT INTO " . $target . " " . $sql);
    $ln = DB::table($target)->count();
    if (!$end) return "Table '" . $target . "' has been reimported with " . $ln . " Records.";
  }

  public function ximport($source, $target, $sql) {
    $temp = 'test_'.$target;
    DB::statement('DROP TABLE IF EXISTS ' . $temp);
    DB::statement('CREATE TABLE ' . $temp . ' like '. $target);
    $sourcedata = DB::connection('mysql2')->select(DB::raw($sql));
    $ln = 1;
    foreach($sourcedata as $src) {
      $ln++;
      $data =   @json_decode(json_encode($src), true);
      DB::table($temp)->insert( $data );
    }
    return $source . '->' . $target . '<br>'. 'insert ' . $target . ' ' . $ln . ' Records inserted';
  }
}

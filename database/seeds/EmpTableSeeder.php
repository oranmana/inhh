<?php

use Illuminate\Database\Seeder;

class EmpTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = "INSERT INTO emps"
        . " SELECT 
            emp_id as `id`,
            emp_dir as `dirid`,
            emp_sid as `empcode`,
            emp_cm as `cm`,
            emp_nm as `nm`,
            emp_name as `name`,
            emp_tname as `thname`,
            emp_date as `indate`,
            emp_retire as `retireage`,
            emp_retired as `xdate`,
            emp_q as `qcode`,
            emp_qdate as `qdate`,

            emp_bplace as `bplace`,
            emp_relg as `relg`,
            emp_raddr as `address`,
            emp_house as `house`,
            emp_tel as `tel`,
            emp_mtel as `mobile`,
            emp_edu as `edu`,
            emp_car as `car`,
            emp_blood as `blood`,
            emp_wg as `weight`,
            emp_hg as `height`,
            emp_military as `military`,

            emp_cls as `cls`,
            emp_org as `org`,
            emp_cm_pos as `post`,
            emp_job as `job`,
            erp.c_erp as `cc`,

            emp_fdate as `pvdate`,
            emp_fcode as `pvcode`,
            pay_fund as `pvcom`,
            emp_fund as `pvemp`,
            emp_fedate as `pvxdate`,

            pay_amt as `pwage`,
            pay_pos as `ppost`,
            pay_food as `pfood`,
            pay_house as `phouse`,
            if(emp_cm=553,0,pay_live) as `pcls`,
            if(emp_cm=553,pay_live,0) as `plive`,
            pay_trans as `pfuel`,
            pay_tuition as `pedu`,
            if(emp_id in (477,638,769), 0, pay_oth) as `pdg`,
            if(emp_id in (477,638,769), pay_oth,0) as `pmove`,

            emp_code as `taxcode`,
            emp_sex as `sex`,
            emp_nation as `nation`,
            emp_bdate as `bdate`,
            emp_addr as `haddr`,
            emp_card as `cardno`,

            5 as `CREATED_BY`, 
            now() as `CREATED_AT`, 
            5 as `UPDATED_BY`, 
            now() as `UPDATED_AT` 

        FROM admin_hctdb.tbemployee
            LEFT JOIN admin_hctdb.tbcommon AS erp ON erp.c_id = emp_erp
        ORDER BY emp_id;";
        
        DB::statement($sql);
    }
}

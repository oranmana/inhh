<?php

use Illuminate\Database\Seeder;

class CalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = "INSERT INTO hctcal"
        . " SELECT 
            cl_id as `id`, 
            cl_date as `cldate`, 
            cl_holiday as `holiday`, 
            cl_des as `rem`,
            if(date_format(cl_date,'%w') in (0,6), 3683, 3678) as `of`,
            at_ofw as `ofm`, 
            at_ofa as `ofo`,
            if(date_format(cl_date,'%w') in (0), 3683, 3679) as `wf`,
            at_wfw as `wfm`, 
            at_wfa as `wfo`,
            5 as `CREATED_BY`, 
            now() as `CREATED_AT`, 
            5 as `UPDATED_BY`, 
            now() as `UPDATED_AT` 
        FROM admin_hctdb.hr_cal
        ORDER BY cl_date;";

        DB::statement($sql);

    }
}

<?php

use Illuminate\Database\Seeder;

class EmpJobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = "INSERT INTO hrjobs"
        . " SELECT 
            j_id as `id`, 
            j_emp as `empid`, 
            j_job as `jobid`, 
            j_date as `since`,
            j_status as `state`, 
            5 as `CREATED_BY`, 
            NOW() AS `CREATED_AT`, 
            5 AS `UPDATED_BY`, 
            NOW() AS `UPDATED_AT`
        FROM admin_hctdb.hr_emp_job
            LEFT JOIN admin_hctdb.tbemployee ON emp_id = j_emp
        WHERE emp_id IS NOT NULL
        ORDER BY j_id;";

        DB::statement($sql);
    }
}

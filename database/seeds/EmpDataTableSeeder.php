<?php

use Illuminate\Database\Seeder;

class EmpDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = "INSERT INTO empsdata"
        . " SELECT 
            0 as `id`,
            1 as `grp`,
            ed_emp as `empid`,
            c_id as `code`,
            ed_inst as `ref`,
            ed_year as `yr`,
            ed_instt as `name`,
            ed_rem as `rem`,
            ed_place as `loc`,
            ed_grd as `grd`,
            1 as `state`,
            5 as `CREATED_BY`,
            now() as `CREATED_AT`,
            5 as `UPDATED_BY`,
            now() as `UPDATED_AT`,
            ed_id as `oid`
        FROM admin_hctdb.hr_emp_edu
            LEFT JOIN (select * from admin_hctdb.tbcommon where c_parent = 3224 ) as cm ON c_code = ed_level
        ORDER BY ed_id;";

        DB::statement($sql);
    }
}

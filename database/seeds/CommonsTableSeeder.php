<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = "INSERT INTO commons"
        . " SELECT 
            c_id as `id`,
            c_parent as `par`,
            c_main as `main`,
            c_num as `num`,
            c_code as `code`,
            c_name as `name`,
            c_tname as `tname`,
            c_des as `des`,
            c_sub as `sub`,
            c_type as `type`,
            c_group as `group`,
            c_cat as `cat`,
            c_ref as `ref`,
            c_off as `off`,
            c_dir as `dir`,
            com_gl as `gl`,
            com_sfx as `sfx`,
            c_node as `node`,
            c_pj as `pj`,
            c_erp as `erp`,
            0 as `CREATED_BY`,
            now() as `CREATED_AT`,
			0 as `UPDATED_BY`,
            now() as `UPDATED_AT`
        FROM admin_hctdb.tbcommon
        ORDER BY c_id;";
        
        DB::statement($sql);

    }
}

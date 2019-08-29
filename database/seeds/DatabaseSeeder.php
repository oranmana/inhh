<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(UsersTableSeeder::class);
//        $this->call(CommonsTableSeeder::class);
//        $this->call(CalTableSeeder::class);

//* $this->call(DirEmpTableSeeder::class);
//* $this->call(DirRelativeTableSeeder::class);

//        $this->call(EmpTableSeeder::class);
$this->call(EmpDataTableSeeder::class);
//  $this->call(EmpJobsTableSeeder::class);

    }
}

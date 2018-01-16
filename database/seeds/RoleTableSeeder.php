<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_employee = new Role();
        $role_employee->name = 'Student';
        $role_employee->description = 'A Student User';
        $role_employee->save();

        $role_manager = new Role();
        $role_manager->name = 'Teacher';
        $role_manager->description = 'A Teacher User';
        $role_manager->save();

        $role_manager = new Role();
        $role_manager->name = 'Admin';
        $role_manager->description = 'An Admin User';
        $role_manager->save();

    }
}

<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $role_student = Role::where('name', 'Student')->first();
        $role_teacher = Role::where('name', 'Teacher')->first();
        $role_admin = Role::where('name', 'Admin')->first();

        $student = new User();
        $student->name = 'Yidir BOUHADJER';
        $student->email = 'yidirbouhadjer@gmail.com';
        $student->password = bcrypt('secret');
        $student->save();
        $student->roles()->attach($role_student);

        $teacher = new User();
        $teacher->name = 'Kamel Draa';
        $teacher->email = 'mr.draa@gmail.com';
        $teacher->password = bcrypt('secret');
        $teacher->save();
        $teacher->roles()->attach($role_teacher);

        $admin = new User();
        $admin->name = 'Hicham Talbi';
        $admin->email = 'hicham.talbi@gmail.com';
        $admin->password = bcrypt('secret');
        $admin->save();
        $admin->roles()->attach($role_admin);
    }
}

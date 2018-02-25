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


        $student = new User();
        $student->name = 'Yidir BOUHADJER';
        $student->email = 'yidirbouhadjer@gmail.com';
        $student->password = bcrypt('secret');
        $student->role = 'S';
        $student->save();

        $teacher = new User();
        $teacher->name = 'Kamel Draa';
        $teacher->email = 'mr.draa@gmail.com';
        $teacher->password = bcrypt('secret');
        $teacher->role = 'T';
        $teacher->save();

        $admin = new User();
        $admin->name = 'Hicham Talbi';
        $admin->email = 'hicham.talbi@gmail.com';
        $admin->password = bcrypt('secret');
        $admin->role = 'A';
        $admin->save();
    }
}

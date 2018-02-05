<?php

use Illuminate\Database\Seeder;
use App\Resource;
use App\User;

class ResourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $talbi = User::find(3);
        $yidir = User::find(1);

        $first_ressource = new Resource();
        $first_ressource->title = 'TP N4';
        $first_ressource->description = 'Bonjour, voici the fourth tp!';
        $first_ressource->google_drive = '0B8JXivo4iMxJVE5pd0s5aE04TjhTT2dWNEVvckhMdGRyT0Iw';
        $first_ressource->publish_year = 2017;
        $first_ressource->user()->associate($talbi);
        $first_ressource->save();

        $second_ressource = new Resource();
        $second_ressource->title = 'TP N5';
        $second_ressource->description = 'Bonjousr, voici the fourfgth tp!';
        $second_ressource->google_drive = '0ssB8JXivo4iMxJVE5pd0s5aE04TjhTT2dWNEVvckhMdGRyT0Iw';
        $second_ressource->publish_year = 2016;
        $second_ressource->user()->associate($yidir);
        $second_ressource->save();


    }
}

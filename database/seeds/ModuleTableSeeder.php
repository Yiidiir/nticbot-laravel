<?php

use Illuminate\Database\Seeder;
use App\Module;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $analyse1 = new Module();
        $analyse1->name = 'Analyse 1';
        $analyse1->code = 'ANA1';
        $analyse1->description = 'Module d\'Analyse 1 première année Licence';
        $analyse1->degree = 'L';
        $analyse1->semester = 1;
        $analyse1->save();

        $algebre2 = new Module();
        $algebre2->name = 'Algèbre 2';
        $algebre2->code = 'ALG2';
        $algebre2->description = 'Module d\'Algèbre 2 première année Licence';
        $algebre2->degree = 'L';
        $algebre2->semester = 2;
        $algebre2->save();

        $poo = new Module();
        $poo->name = 'Programmation Orientée Objet';
        $poo->code = 'POO';
        $poo->description = 'Module d\'Analyse première année Licence';
        $poo->degree = 'L';
        $poo->semester = 1;
        $poo->save();


    }
}

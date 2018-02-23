<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{

    protected $fillable = ['name', 'code', 'description', 'degree', 'semester'];

    //

    public function resources()
    {
        $this->hasMany(Resource::class);
    }

    public static function getFormModulesArray()
    {
        $modules = Module::get(['name', 'degree', 'semester', 'code', 'id'])->sortBy('semester')->groupBy('degree')->toArray();

        $m_form = array();
        $i = 0;
        foreach ($modules as $degree_key => $degree) {

            foreach ($degree as $k_module => $module) {
                $m_form[$degree_key][$module['id']] = 'S' . $module['semester'] . ' > ' . $module['name'];
            }
        }

        array_key_exists('L', $m_form) ? ($m_form['Licence'] = $m_form['L'] AND $m_form = array_diff_key($m_form, array_flip((array)['L']))) : NULL;
        array_key_exists('M', $m_form) ? ($m_form['Master'] = $m_form['M'] AND $m_form = array_diff_key($m_form, array_flip((array)['M']))) : NULL;
        array_key_exists('D', $m_form) ? ($m_form['Doctorat'] = $m_form['D'] AND $m_form = array_diff_key($m_form, array_flip((array)['D']))) : NULL;

        return $m_form;
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    //

    public function resources()
    {
        $this->hasMany(Resource::class);
    }
}

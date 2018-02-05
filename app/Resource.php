<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    //

    /** Get the ressource poster
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

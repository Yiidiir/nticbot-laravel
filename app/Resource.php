<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    //

    protected $fillable = ['title','description','google_drive','publish_year','module_id'];


    /** Get the ressource poster
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}

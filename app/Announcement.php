<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    //

    protected $fillable = ['body', 'planned_time'];



    public function user()
    {
        $this->belongsTo(User::class);
    }
}

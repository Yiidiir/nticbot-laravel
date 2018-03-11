<?php

namespace App;

use App\Observers\AnnouncementObserver;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    //

    protected $fillable = ['body', 'planned_time'];



    public function user()
    {
        $this->belongsTo(User::class);
    }

    public static function boot() {
        parent::boot();

        Announcement::observe(new AnnouncementObserver());
    }
}

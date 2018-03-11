<?php
/**
 * Created by PhpStorm.
 * User: Yidir
 * Date: 3/9/2018
 * Time: 7:46 PM
 */

namespace App\Observers;


use App\Announcement;
use App\User;
use Illuminate\Http\Request;
use pimax\FbBotApp;
use pimax\Messages\Message;

class AnnouncementObserver
{

    public function created(Announcement $announcement)
    {
        $subscribers = User::whereNotNull('messenger_uid')->where('sub_announcements', 1)->get();

        if (!$subscribers->isEmpty()) {
            $bot = new FbBotApp(env('MESSENGER_VERIFY_TOKEN', false));
            foreach ($subscribers as $subscriber) {
                $bot->send(new Message($subscriber->messenger_uid, '📢📢 New Announcement Broadcasted! 📢📢'));
                $bot->send(new Message($subscriber->messenger_uid, $announcement->body));
            }
        }
    }
}
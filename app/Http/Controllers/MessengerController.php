<?php

namespace App\Http\Controllers;

use Event;

use App\Announcement;
use App\Module;
use App\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use pimax\FbBotApp;
use pimax\Menu\MenuItem;
use pimax\Menu\LocalizedMenu;
use pimax\Messages\Message;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageElement;
use pimax\Messages\ImageMessage;
use pimax\Messages\QuickReply;
use pimax\Messages\QuickReplyButton;
use App\User;

class MessengerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $verify_token = env('MESSENGER_APP_TOKEN', false);
        $token = env('MESSENGER_VERIFY_TOKEN', false);

        // Starte a new Bot Instance
        $bot = new FbBotApp($token);
        // Checking if the Facebook API is checking the URL
        if (!empty($request->hub_mode) && $request->hub_mode == 'subscribe' && $request->hub_verify_token == $verify_token) {

            echo $request->get('hub_challenge');
        } else {


            if (!empty($request->entry[0]['messaging'])) {
                foreach ($request->entry[0]['messaging'] as $message) {

                    // Skipping delivery messages
                    if (!empty($message['delivery'])) {
                        continue;
                    }


                    // skip the echo of my own messages
                    if (isset($message['message']['is_echo']) && $message['message']['is_echo'] == "true") {
                        continue;
                    }


                    $command = "";

                    // When bot receive message from user
                    if (!empty($message['message'])) {
                        if (!empty($message['message']['text'])) {
                            $command = $message['message']['text'];
                        }
                        if (!empty($message['message']['quick_reply']['payload'])) {
                            $command = $message['message']['quick_reply']['payload'];
                        }

                        // When bot receive button click from user
                    } else if (!empty($message['postback'])) {
                        $command = $message['postback']['payload'];
                    }

                    if ($command == 'GET_STARTED') {

                        $messenger_user = $bot->userProfile($message['sender']['id']);
                        $user = User::where(['messenger_uid' => $message['sender'], 'role' => 'S'])->first();
                        if (!$user) {
                            $user = User::create([
                                'name' => $messenger_user->getFirstName() . ' ' . $messenger_user->getLastName(),
                                'messenger_uid' => $message['sender']['id'],
                                'role' => 'S'
                            ]);
                            $bot->send(new Message($message['sender']['id'], 'Welcome, ' . $messenger_user->getFirstName() . '!'));
                        } else {
                            $bot->send(new Message($message['sender']['id'], 'Welcome back, ' . $messenger_user->getFirstName()));
                        }

                        sleep(2);

                        $bot->send(new Message($message['sender']['id'], 'You\'ll be getting the latest updates & announcements from NTIC on-time!'));
                        sleep(2);
                        $bot->send(new QuickReply($message['sender']['id'], 'You can instantly download study resources too 👇', []));
                        $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Licence Degree', '[[DL]]');
                        $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Master Degree', '[[DM]]');
                        $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Doctorate Degree', '[[DD]]');

                        $bot->send(new QuickReply($message['sender']['id'], '◀👆▶', $actions));


                        break;
                    } else {

                        switch ($command) {

                            case 'SHOW_RESOURCES_MAIN':
                                choose_degree:
                                $bot->send(new QuickReply($message['sender']['id'], 'Choose your degree ;D', []));
                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Licence Degree', '[[DL]]');
                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Master Degree', '[[DM]]');
                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Doctorate Degree', '[[DD]]');
                                $bot->send(new QuickReply($message['sender']['id'], '◀👆▶', $actions));
                                break;

                            // When bot receive "image"
                            case 'local image':
                                $bot->send(new ImageMessage($message['sender']['id'], dirname(__FILE__) . '/fb4d_logo-2x.png'));
                                break;


                            case (preg_match_all('/\[\[D(L|M|D)\]\]/', $command, $matches, PREG_SET_ORDER, 0) ? TRUE : FALSE):

                                switch ($matches[0][1]) {
                                    case 'L':
                                        modules_sem_l:
                                        $modules_sem = Module::where(['degree' => 'L'])->distinct()->pluck('semester')->toArray();
                                        if (!empty($modules_sem)) {
                                            foreach ($modules_sem as $semester) {
                                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Semester ' . $semester, 'GET_MODULES_LICENCE_SEMESTER_' . $semester);
                                            }
                                            $bot->send(new QuickReply($message['sender']['id'], 'What semester I mean?', $actions));
                                        } else {
                                            $bot->send(new Message($message['sender']['id'], 'Nothing found :('));
                                            sleep(3);
                                            goto choose_degree;
                                        }
                                        break;
                                    case 'M':
                                        modules_sem_m:
                                        $modules_sem = Module::where(['degree' => 'M'])->distinct()->pluck('semester')->toArray();
                                        if (!empty($modules_sem)) {
                                            foreach ($modules_sem as $semester) {
                                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Semester ' . $semester, 'GET_MODULES_MASTER_SEMESTER_' . $semester);
                                            }
                                            $bot->send(new QuickReply($message['sender']['id'], 'What semester I mean?', $actions));
                                        } else {
                                            $bot->send(new Message($message['sender']['id'], 'Nothing found :('));
                                            sleep(3);
                                            goto choose_degree;
                                        }
                                        break;
                                    case 'D':
                                        modules_sem_d:
                                        $modules_sem = Module::where(['degree' => 'D'])->distinct()->pluck('semester')->toArray();
                                        if (!empty($modules_sem)) {
                                            foreach ($modules_sem as $semester) {
                                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Semester ' . $semester, 'GET_MODULES_DOCTORATE_SEMESTER_' . $semester);
                                            }
                                            $bot->send(new QuickReply($message['sender']['id'], 'What semester I mean?', $actions));
                                        } else {
                                            $bot->send(new Message($message['sender']['id'], 'Nothing found :('));
                                            sleep(3);
                                            goto choose_degree;
                                        }
                                        break;
                                }
                                break;


                            case (preg_match_all('/GET_MODULES_(LICENCE|MASTER|DOCTORATE)_SEMESTER_(\d)/', $command, $matches, PREG_SET_ORDER, 0) ? TRUE : FALSE):
                                switch ($matches[0][1]) {
                                    case 'LICENCE':
                                        $modules = Module::where(['degree' => 'L', 'semester' => $matches[0][2]])->has('resources')->distinct()->get()->toArray();
                                        if (count($modules) > 0) {
                                            foreach ($modules as $module) {
                                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, $module['name'], 'GET_RESOURCES_' . $module['code']);
                                            }
                                            $bot->send(new QuickReply($message['sender']['id'], 'Choose which module? :D', $actions));
                                        } else {
                                            $bot->send(new Message($message['sender']['id'], 'Nothing found :('));
                                            goto choose_degree;
                                        }
                                        break;
                                    case 'MASTER':
                                        $modules = Module::where(['degree' => 'M', 'semester' => $matches[0][2]])->has('resources')->distinct()->toArray();
                                        if (count($modules) > 0) {
                                            foreach ($modules as $module) {
                                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, $module['name'], 'GET_RESOURCES_' . $module['code']);
                                            }
                                            $bot->send(new QuickReply($message['sender']['id'], 'Choose which module? :D', $actions));
                                        } else {
                                            $bot->send(new Message($message['sender']['id'], 'Nothing found :('));
                                            goto choose_degree;
                                        }
                                        break;
                                    case 'DOCTORATE':
                                        $modules = Module::where(['degree' => 'D', 'semester' => $matches[0][2]])->has('resources')->distinct()->toArray();
                                        if (count($modules) > 0) {
                                            foreach ($modules as $module) {
                                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, $module['name'], 'GET_RESOURCES_' . $module['code']);
                                            }
                                            $bot->send(new QuickReply($message['sender']['id'], 'Choose which module? :D', $actions));
                                        } else {
                                            $bot->send(new Message($message['sender']['id'], 'Nothing found :('));
                                            goto choose_degree;
                                        }
                                        break;


                                }
                                break;

                            case (preg_match_all('/GET_RESOURCES_(.+)/', $command, $matches, PREG_SET_ORDER, 0) ? TRUE : FALSE):
                                $resources = Resource::whereHas('module', function ($q) use ($matches) {
                                    $q->where('code', $matches[0][1]);
                                })->latest()->get();

                                if (!$resources->isEmpty()) {

                                    $elems = [];
                                    foreach ($resources as $resource) {
                                        $elems[] = new MessageElement($resource->title . ' (' . $resource->publish_year . ' - ' . ($resource->publish_year + 1) . ')', $resource->description, 'http://icons.iconarchive.com/icons/zhoolego/material/512/Filetype-Docs-icon.png', [
                                            new MessageButton(MessageButton::TYPE_WEB, 'Download', $resource->google_drive),
                                            new MessageButton(MessageButton::TYPE_WEB, 'View', route('resources.show', $resource->id)),
                                        ]);
                                    }
                                    $bot->send(new StructuredMessage($message['sender']['id'],
                                        StructuredMessage::TYPE_GENERIC,
                                        [
                                            'elements' => $elems
                                        ]
                                    ));
                                } else {
                                    $bot->send(new Message($message['sender']['id'], 'No resources found, sorry :('));
                                    goto choose_degree;
                                }
                                break;


                            case 'TOGGLE_SUB':
                                $user = User::where('messenger_uid', $message['sender']['id'])->get()->first();
                                if (!$user !== null) {
                                    echo $user->update(['sub_announcements' => !$user->sub_announcements]);
                                    $bot->send(new Message($message['sender']['id'], 'Announcements settings updated!'));
                                } else {
                                    $bot->send(new Message($message['sender']['id'], 'I don\'t know you!'));
                                }
                                break;

                            case 'ASK_ANNOUNCEMENTS':
                                $announcements = Announcement::where('planned_time','<=',Carbon::now()->toDateTimeString())->latest()->take(5)->get();
                                if(!$announcements->isEmpty()) {
                                    foreach ($announcements as $announcement) {
                                        $bot->send(new Message($message['sender']['id'], $announcement->body . ''));
                                    }
                                } else {
                                    $bot->send(new Message($message['sender']['id'], 'Huh, No Announcements Found!'));
                                }
                                break;


                            // Other message received
                            default:
                                $sorry = ['Sorry, i didn\'t understand you 😬', 'Sorry, but I didn\'t understand what you meant by ' . $command . ' :| ', 'Hmm... i could\'t guess what would ' . $command . ' refer to :|', 'Couldn\'t understand... Remember you can always check Help :)'];
                                $bot->send(new Message($message['sender']['id'], $sorry[array_rand($sorry)]));
                                break;
                        }
                    }

                }

            }


        }
    }

    /*
     * TODO:
     * #Fix Index resources & announcements layout
     * #add pagination for users by type
     * setup drive api
     * use pusher for announcements
     * setup cron for delayed announcements
     * use Listeners for Notifs (Messenger)
     * Make Messenger as a Service
     * Add Language Support
     * */
}

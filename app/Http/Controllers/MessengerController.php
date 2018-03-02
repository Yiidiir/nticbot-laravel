<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use pimax\FbBotApp;
use pimax\Menu\MenuItem;
use pimax\Menu\LocalizedMenu;
use pimax\Messages\Message;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageElement;
use pimax\Messages\MessageReceiptElement;
use pimax\Messages\Address;
use pimax\Messages\Summary;
use pimax\Messages\Adjustment;
use pimax\Messages\AccountLink;
use pimax\Messages\ImageMessage;
use pimax\Messages\QuickReply;
use pimax\Messages\QuickReplyButton;
use pimax\Messages\SenderAction;

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
                    $user_id = 0;

                    // When bot receive message from user
                    if (!empty($message['message'])) {
                        $command = $message['message']['text'];
                        if (!empty($message['message']['quick_reply']['payload'])) {
                            $command = $message['message']['quick_reply']['payload'];
                        }

                        // When bot receive button click from user
                    } else if (!empty($message['postback'])) {
                        $command = $message['postback']['payload'];
                    }

                    if (isset($message['account_linking']['status']) && $message['account_linking']['status'] == 'unlinked') {
/*                        $connection = new Database();
                        $user = new User($connection, FALSE, $message['sender']['id'], FALSE);
                        $user->logout();*/
                        $bot->send(new Message($message['sender']['id'], 'Logged Out!'));

                    }
                    if (isset($message['account_linking']['status']) && $message['account_linking']['status'] == 'linked') {
                        $connection = new Database();
                        $user = new User($connection, $message['account_linking']['authorization_code'], FALSE, FALSE);
                        $user->setMessengerId($message['sender']['id']);
                        $user_id = $user->get('messenger_id');

                        $bot->send(new Message($message['sender']['id'], 'Hi ' . $user->get('username') . '!'));

                        $bot->send(new StructuredMessage($message['sender']['id'],
                            StructuredMessage::TYPE_BUTTON,
                            [
                                'text' => 'What do you want to do?',
                                'buttons' => [
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Manage Devices'),
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Help'),
                                    new MessageButton(MessageButton::TYPE_POSTBACK, 'Logout')
                                ]
                            ]
                        ));
                        break;
                    } elseif ($command == 'newbie_user') {
                        $bot->send(new Message($message['sender']['id'], "Hi there, thanks for choosing to be a Beta Tester for our bot!"));
                        sleep(3);
                        $bot->send(new Message($message['sender']['id'], "Please Login or Signup with a Circwit account to start"));
                        sleep(2);
                        $bot->send(new StructuredMessage($message['sender']['id'],
                            StructuredMessage::TYPE_GENERIC,
                            [
                                'elements' => [
                                    new AccountLink(
                                        'Signin to Circwit',
                                        'Please login to your account to see all your devices and start managing them.',
                                        'https://circwit.co/api/oauth/authorize',
                                        'https://circwit.co/api/img/circwit_control.png')
                                ]
                            ]
                        ));
                        sleep(5);
                        $bot->send(new Message($message['sender']['id'], "You can always send 'Help' (without quotes) to get some help :p"));


                        break;
                    } else {

//                        $connection = new Database();
//                        try {
//                            $user = new User($connection, FALSE, $message['sender']['id'], FALSE);
//                            $user_id = $user->getUid();
//                            $devices = $user->getDevices();
//                        } catch (Exception $e) {
//                            if ($e->getMessage() == 'NONE') {
//                                $command = 'login';
//                            }
//                        }


                        switch ($command) {

                            // When bot receive "text"
                            case 'text':
                                $bot->send(new Message($message['sender']['id'], 'This is a simple text message.'));
                                break;

                            // When bot receive "image"
                            case 'image':
                                $bot->send(new ImageMessage($message['sender']['id'], 'https://developers.facebook.com/images/devsite/fb4d_logo-2x.png'));
                                break;

                            // When bot receive "image"
                            case 'local image':
                                $bot->send(new ImageMessage($message['sender']['id'], dirname(__FILE__) . '/fb4d_logo-2x.png'));
                                break;

                            // When bot receive "profile"
                            case 'profile':

                                $user = $bot->userProfile($message['sender']['id']);
                                $bot->send(new StructuredMessage($message['sender']['id'],
                                    StructuredMessage::TYPE_GENERIC,
                                    [
                                        'elements' => [
                                            new MessageElement($user->getFirstName() . " " . $user->getLastName(), " ", $user->getPicture())
                                        ]
                                    ]
                                ));

                                break;

                            // When bot receive "button"
                            case 'button':
                                $arr = [];
                                foreach ($devices as $device) {
                                    $arr[] = new MessageButton(MessageButton::TYPE_POSTBACK, 'First button');
                                }

                                $bot->send(new StructuredMessage($message['sender']['id'],
                                    StructuredMessage::TYPE_BUTTON,
                                    [
                                        'text' => 'Choose category',
                                        'buttons' => $arr
                                    ]
                                ));
                                break;


                            case ($command == 'helpmenu' || $command == 'Help'):
                                $bot->send(new QuickReply($message['sender']['id'], "Circwit Messenger Bot lets you remote-control your IoT-based solutions (Tested with Raspberry Pi) inside Messenger without any third-party apps even when there's no internet (Freebasics compatible), so what do you need help about?", $actions));
                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'About & use', 'about_use');
                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Adding Devices', 'adding_devices');
                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Controlling Devices', 'controlling_devices');
                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Downtime Updates', 'downtime_updates');
                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Freebasics commands', 'freebasics_cmds');
                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Remove a Device', 'remove_device');
                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'Configure SSH (RPI)', 'configure_ssh');
                                $actions[] = new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'What\'s next?', 'wat_next');

                                $bot->send(new QuickReply($message['sender']['id'], '◀👆▶', $actions));
                                break;


                            // Taking care of all the help menu cases ;p

                            case 'text_curl':
                                $bot->send(new Message($message['sender']['id'], '💡 To interact from your device and send a message to the Messenger conversation, execute this in your command line: '));
                                $bot->send(new Message($message['sender']['id'], 'curl -X POST -F \'email=random11@gmail.com\' -F \'password=yidir\' -F \'text=Noiice!\' https://circwit.co/api/device/text'));
                                $bot->send(new Message($message['sender']['id'], '👉 Sending images,audio and video will be possible by the next update!'));

                                break;

                            case 'about_use':
                                $bot->send(new Message($message['sender']['id'], 'The Circwit Bot is a simple tool that allows you to control your IoT device (Tested with Raspberry Pi), it features some cool ways to directly interact with your solution in a cool way allowing you to send/receive queries to/from the device'));
                                sleep(2);
                                $bot->send(new Message($message['sender']['id'], 'One of the uses of Circwit Bot is replacing the basic SMS solution (obsolete,not stable and slow) widely used of notifications with Messenger.. Even more, for exchanging various data types such as images,videos and audio (will be supported in the next version) to enlarge even more what can be build in Messenger'));
                                sleep(2);
                                $bot->send(new Message($message['sender']['id'], 'Another use is permitting more interaction with smart solutions, involving AI for example (in short).'));
                                break;


                            case 'adding_devices':
                                $bot->send(new Message($message['sender']['id'], '💡 To add a new device to the system please make sure that an SSH server is up, running and accessible in the device being added. Once SSH is ready enter this command in your device console (without quotes): "python3 <(curl https://circwit.co/api/circwit.py)" and follow the instructions!'));
                                sleep(2);
                                $bot->send(new Message($message['sender']['id'], '💡 You\'ll get a success message in both the console window and this chat at the end of the configuration.'));
                                break;
                            case 'controlling_devices':
                                $bot->send(new Message($message['sender']['id'], '💡 An SSH connection is used to access the console, and that how you can control your device.'));
                                sleep(2);
                                $bot->send(new Message($message['sender']['id'], '💡 You can control your device with a set of commands that you can customize in the Manage Commands menu'));
                                sleep(2);
                                $bot->send(new Message($message['sender']['id'], '💡 Once a command its added with it set of console command, you can use it in the Control Device menu'));
                                sleep(2);
                                $bot->send(new Message($message['sender']['id'], '💡 A message will notify you whenever a connection has been established, the command was sent to the console, the console reply, the time that each operation took and when the connection was closed.'));

                                break;
                            case 'downtime_updates':
                                $bot->send(new Message($message['sender']['id'], '💡 The Circwit Bot will notify you whenever your device encounters any problem (eg. shutting down or network error) with a message within 60 seconds.'));
                                sleep(2);
                                $bot->send(new Message($message['sender']['id'], '💡 If the device was down for 10-30min, the bot will remind you every 8 minutes that your device is still down. If the device was down for 30-60min, the bot will remind you every 15 minutes that your device is still down. If the device was down for 1-12h, the bot will remind you every hour that your device is still down. If the device was down for 12-24h, the bot will remind you every 5 hours that your device is still down. If the device was down for more than 24h, the bot will remind you every 12 hours that your device is still down!'));
                                break;
                            case 'remove_device':
                                $bot->send(new Message($message['sender']['id'], '💡 To remove a device from your list simply type: '));
                                $bot->send(new Message($message['sender']['id'], 'remove device DEVICE_ID'));
                                sleep(2);
                                $bot->send(new Message($message['sender']['id'], 'The device ID can be found under the device\'s details, accessible via the Manage Devices menu.'));
                                sleep(2);
                                $bot->send(new Message($message['sender']['id'], '👉 Removing a device will remove all associated commands.'));
                                break;

                            case 'configure_ssh':

                                $bot->send(new Message($message['sender']['id'], '💡 Here is the official documentation about setting up an SSH server in your Raspberry Pi: '));
                                sleep(1);
                                $bot->send(new Message($message['sender']['id'], 'https://www.raspberrypi.org/documentation/remote-access/ssh/ '));
                                break;

                            case 'wat_next':
                                $bot->send(new Message($message['sender']['id'], 'Here\'s the list of options that we couldn\'t add because of the exams\'s timestamp (as being students), they might be ready by the finals deadline if we make it there :)'));
                                $bot->send(new Message($message['sender']['id'], "1- Redesigning the system to work without SSH (and still accomplish its duty) and limit the commands that our server can execute in the user's device with a technical persuading way (to make it non-confidence related). \n 2- Support sending media (Images, videos and audio) from the device to Messenger. \n 3- Support static IPs"));
                                break;
                            case 'freebasics_cmds':
                                $bot->send(new Message($message['sender']['id'], "Add new device: /add \nList devices: /manage\nShow device details: /details < DEVICE ID >\n List Commands: /commands < DEVICE ID >\nAdd Command: < DEVICE ID > < Command Name > < cmd in console >\nExecute Command: /execute < COMMAND ID >\nEdit Command: < DEVICE ID > < OLD Command Name > < new cmd in console > \n "));
                                $bot->send(new Message($message['sender']['id'], 'DEVICE ID & COMMAND ID can be both found by sending /commands and /manage'));
                                break;


                            // When bot receive "generic"
                            case 'quickreply':
                                $actions = [new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'texot1', 'baby'),
                                    new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'texot2', 'baby2'),
                                    new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'texot3', 'baby3'),
                                    new QuickReplyButton(QuickReplyButton::TYPE_TEXT, 'texot', 'baby4')];
                                $bot->send(new QuickReply($message['sender']['id'], 'Choose or die', $actions));

                                break;

                            case 'baby':
                                $bot->send(new Message($message['sender']['id'], 'HeyHey you wanna ' . $command . ' ?? heuh'));

                                break;

                            case 'set menu':

                                break;

                            case 'delete menu':
                                $bot->deletePersistentMenu();
                                break;

                            case 'login':
                                $bot->send(new StructuredMessage($message['sender']['id'],
                                    StructuredMessage::TYPE_GENERIC,
                                    [
                                        'elements' => [
                                            new AccountLink(
                                                'Signin to Circwit',
                                                'Please login to your account to see all your devices and start managing them.',
                                                'https://circwit.co/api/oauth/authorize',
                                                'https://circwit.co/api/img/circwit_control.png')
                                        ]
                                    ]
                                ));
                                break;

                            case ($command == 'Logout' || $command == 'logout'):
                                $bot->send(new StructuredMessage($message['sender']['id'],
                                    StructuredMessage::TYPE_GENERIC,
                                    [
                                        'elements' => [
                                            new AccountLink(
                                                'Logout from Circwit',
                                                'Close your Circwit session.',
                                                '',
                                                'https://circwit.co/api/img/circwit_control.png',
                                                TRUE)
                                        ]
                                    ]
                                ));
                                $user_id = '';
                                break;

                            case 'ADD_DEVICE':
                                $bot->send(new Message($message['sender']['id'], '💡 Enter this line in your console and follow the instructions in order to add a new device: '));
                                $bot->send(new Message($message['sender']['id'], 'python3 <(curl https://circwit.co/api/circwit.py)'));

                                break;

                            /*
                            Take Control :
                            Show All attached commands to that device!
                            */

                            case ($command == 'take control' || $command == 'Take Control'):
                                $bot->send(new Message($message['sender']['id'], 'Successfully sent order! 8) '));
                                break;
                            case ($command == '4503' || $command == '3250' || preg_match('/^#CTRL#/', $command)):
                                $bot->send(new Message($message['sender']['id'], 'Successfully sent order! 8) '));
                                break;

                            // Other message received
                            default:
                                $sorry = ['Sorry, i didn\'t understand you 😬', 'Sorry, but I didn\'t understand what you meant by ' . $command . ' :| ', 'Hmm... i could\'t guess what would ' . $command . ' refer to :|', 'Couldn\'t understand... Remember you can always check Help :)'];
                                $bot->send(new Message($message['sender']['id'], $sorry[array_rand($sorry)]));
                                sleep(1);
                                if (!empty($command)) { // otherwise "empty message" wont be understood either
                                    $bot->send(new StructuredMessage($message['sender']['id'],
                                        StructuredMessage::TYPE_BUTTON,
                                        [
                                            'text' => 'So, what do you want to do?',
                                            'buttons' => [
                                                new MessageButton(MessageButton::TYPE_POSTBACK, 'Manage Devices'),
                                                new MessageButton(MessageButton::TYPE_POSTBACK, 'Help', 'helpmenu'),
                                                new MessageButton(MessageButton::TYPE_POSTBACK, 'Logout')
                                            ]
                                        ]
                                    ));
                                }
                                break;
                        }
                    }

                }

            }


        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

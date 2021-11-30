<?php
namespace App\Http\Controllers;
   
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;
use App\Http\Conversations\MenuConversation;
   
class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');
   
        $botman->hears('{message}', function($botman, $message) {
   
            if ($message == 'hi') {
                if (auth()->user()) {
                    $name = auth()->user()->name;
                    $botman->reply('Hello there, '.$name);
                } else {
                    $botman->reply('Please Login.');
                }
                $botman->startConversation(new MenuConversation());
                
            } elseif ($message == '\/help') {
                $this->help($botman);
            }
            else {
                $botman->reply("/help See this helping message.🙂");
            }
        });

        // $botman->hears('\/stop', function(BotMan $bot) {
        //     $bot->reply('stopped');
        // })->stopsConversation();
   
        $botman->listen();
    }
   
    /**
     * Place your BotMan logic here.
     */
    public function help($botman)
    {
        $botman->reply("/help See this helping message");
    }
}
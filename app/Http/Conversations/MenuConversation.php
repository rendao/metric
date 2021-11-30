<?php

namespace App\Http\Conversations;

use BotMan\BotMan\BotMan;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Http\Controllers\Api\TestController;
use App\Http\Conversations\TestConversation;

class MenuConversation extends Conversation
{
    public function list()
    {
        
        $question = Question::create('What are you feeling now?')
            ->callbackId('current_feeling')
            ->addButtons([
                Button::create('Depressed')->value('ToPm3bbDT7ZE'),
                Button::create('Anxious')->value('TNjjrBoBzSJS')
        ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue()) {
                    try {
                        $test_controller = new TestController();
                        // TNjjrBoBzSJS ToPm3bbDT7ZE
                        $result = $test_controller->show($answer->getValue());
                        $test = $result->getData();
                        // var_dump($test);
                        $message = '';
                        $message = '<b>'.$test->data->name.'</b>';
                        $message .= '<br>';
                        $message .= $test->data->duration.'s';
                        $message .= '<hr />';
                        $message .= $test->data->short_description;
                        $this->say($message);
                        $this->bot->userStorage()->save([
                            'test' => json_encode($test),
                        ]);
                        $this->bot->startConversation(new TestConversation());
                        
                    } catch(Throwable $e) {
                        $this->say('error...');
                    }
                } 
            }
        });
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->list();
    }
}

<?php

namespace App\Http\Conversations;

use Illuminate\Http\Request;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\ResultController;
use App\Models\Test;

class TestConversation extends Conversation
{
    protected $test = null;
    
    public function ready()
    {
        $question = Question::create('Ready to start?')
        ->callbackId('ready_start')
        ->addButtons([
            Button::create('Yes')->value('yes'),
            Button::create('No')->value('no')
        ]); 
        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue()) {
                    $storage = $this->bot->userStorage()->find();
                    $test = json_decode($storage->get('test'));
                    $this->say($answer->getText());
                    $this->say('Let\'s start the test.');
                    // start test
                    $this->test = $test->data;
                    $this->question();
                }
            }
        });
    }

    public function question()
    {
        $test = $this->test;
        $test_controller = new TestController();
        $test_model = Test::find($test->id);
        // start a test.
        $start = $test_controller->start($test_model);
        $data = $start->getData();
        $current_question = $data->current_question;
        if(empty($current_question)) {
            return $this->result($data->session_code);
        }
        $options = $current_question->options;

        $optionButtons = [];
        foreach ($options as $key => $option) {
            $op = json_encode($option, JSON_UNESCAPED_UNICODE);
            $button = Button::create($option->label)->value($op);
            $optionButtons[] = $button;
        }

        $question = Question::create($current_question->question)
        ->callbackId('question_'.$current_question->id)
        ->addButtons($optionButtons); 

        return $this->ask($question, function (Answer $answer) use($data, $test_controller, $test_model) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue()) {
                    $option = json_decode($answer->getValue());
                    $this->say($option->label);
                    // submit answser
                    $req = [
                        'code' => $data->test->code,
                        'question_code' => $data->current_question->code,
                        'trait' => $data->current_question->trait,
                        'session_code' => $data->session_code,
                        'question_position' => $data->current_question->position,
                        'duration' => 10,
                        'option' => array(
                            'label' => $option->label,
                            'score' => $option->score,
                            'value' => $option->value,
                        ),
                    ];
                    $request = new Request();
                    $request->merge($req);
                    $a = $test_controller->answer($request, $test_model);
                    // questions loop
                    $this->question();
                }
            }
        });
    }

    public function result($session_code)
    {
        $botman = app('botman');
        $result_controller = new ResultController();
        $show = $result_controller->show($session_code);
        $data = $show->getData();
        
        $botman->reply('Thanks!  Report about:'.$data->test->name);
        $botman->reply($data->result);
    }
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->ready();
    }
}

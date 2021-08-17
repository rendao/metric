<?php

namespace App\Models;

use App\Models\Test;
use App\Models\User;
use App\Models\Question;
use App\Models\TestSession;

use Illuminate\Database\Eloquent\Model;

class QuestionSession extends Model
{
    protected $table = 'question_sessions';

    public function user()
    {
      return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function test()
    {
      return $this->belongsTo(Test::class, 'test_id', 'id');
    }

    public function question()
    {
      return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function test_session()
    {
      return $this->belongsTo(TestSession::class, 'test_session_id', 'id');
    }
}

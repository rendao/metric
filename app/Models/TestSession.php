<?php

namespace App\Models;

use App\Models\Test;
use App\Models\User;
use App\Models\Question;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestSession extends Model
{
    use SoftDeletes;

    protected $table = 'test_sessions';

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
      return $this->belongsTo(Question::class, 'current_question_id', 'id');
    }

}

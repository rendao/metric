<?php

namespace App\Models;

use App\Models\Test;
use App\Models\User;
use App\Models\Question;
use App\Models\QuestionSession;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TestSession extends Model
{
    use SoftDeletes;

    protected $casts = [
        'result' => 'array',
    ];
    protected $table = 'test_sessions';
    protected $fillable = ['test_id', 'user_id', 'duration', 'current_question_id', 'start_at', 'end_at', 'status'];
    protected $hidden = ['id', 'test_id', 'user_id', 'created_at', 'updated_at', 'deleted_at'];
    
    // Computed
    // protected $appends = ['total_time_taken'];
    // public function getTotalTimeTakenAttribute(){
    //     return ;
    // }
    
    // public function getResultAttribute()
    // {
    //     return json_decode($this->attributes['result']);
    // }

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

    public function question_sessions()
    {
      return $this->hasMany(QuestSession::class, 'test_session_id', 'id');
    }
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_sessions')
            ->withPivot('status', 'option', 'duration');
    }

    protected static function booted()
    {
        static::creating(function ($m) {
            $m->attributes['code'] = Str::uuid();
        });
    } 

}

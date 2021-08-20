<?php

namespace App\Models;

use App\Models\Test;
use App\Models\User;
use App\Models\Question;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TestSession extends Model
{
    use SoftDeletes;

    protected $table = 'test_sessions';
    protected $fillable = ['test_id', 'user_id', 'start_at', 'end_at', 'status'];
    protected $hidden = ['created_at', 'updated_at'];

    // protected $appends = ['total_time_taken'];
    // Computed
    // public function getTotalTimeTakenAttribute(){
    //     return ;
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

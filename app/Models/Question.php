<?php

namespace App\Models;

use App\Models\Test;
use App\Models\QuestionType;
use App\Models\QuestionSession;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';
    protected $hidden = ['id', 'test_id', 'question_type_id', 'created_at', 'updated_at'];

    protected $casts = [
        'options' => 'json',
    ];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function test()
    {
      return $this->belongsTo(Test::class, 'test_id', 'id');
    }
  
    public function question_type()
    {
      return $this->belongsTo(QuestionType::class, 'question_type_id', 'id');
    }

    public function question_session()
    {
      return $this->hasMany(QuestionSession::class);
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'questions', 'question_id', 'test_id');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getColumnNameAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }

    public function setColumnNameAttribute($value)
    {
        $this->attributes['options'] = json_encode(array_values($value));
    }


   protected static function booted()
   {
       static::creating(function ($question) {
           $question->attributes['code'] = 'Q'.Str::random(11);
       });
   }
}

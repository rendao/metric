<?php

namespace App\Models;

use App\Models\Test;
use App\Models\QuestionType;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';
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
       static::creating(function ($category) {
           $category->attributes['code'] = 'T-'.Str::random(11);
       });
   }
}

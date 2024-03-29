<?php

namespace App\Models;

use App\Models\Test;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TestScore extends Model
{
    protected $table = 'test_scores';

    protected $hidden = ['id', 'test_id', 'created_at', 'updated_at', 'deleted_at'];

    public function test()
    {
      return $this->belongsTo(Test::class, 'test_id', 'id');
    }

    protected static function booted()
    {
        static::creating(function ($test_scores) {
            $test_scores->attributes['code'] = 'S'.Str::random(11);
        });
    }

}

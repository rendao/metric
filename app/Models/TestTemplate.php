<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TestTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'test_templates';

    protected static function booted()
    {
        static::creating(function ($test_templates) {
            $test_templates->attributes['code'] = 'TT-'.Str::random(11);
        });
    }
}

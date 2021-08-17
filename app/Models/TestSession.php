<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestSession extends Model
{
    use SoftDeletes;

    protected $table = 'test_sessions';
}

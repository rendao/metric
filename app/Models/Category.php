<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use Sluggable;
    use SoftDeletes;

    protected $table = 'categories';

    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

   protected static function booted()
   {
       static::creating(function ($category) {
           $category->attributes['code'] = 'C'.Str::random(11);
       });
   }

}

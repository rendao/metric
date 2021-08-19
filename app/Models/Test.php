<?php

namespace App\Models;

use App\Models\Category;
use App\Models\TestType;
use App\Models\TestTemplate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Test extends Model
{
    use HasFactory;

    protected $table = 'tests';

    protected $hidden = [
        'compute_api',
        'created_at',
        'updated_at'
    ];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function category()
    {
      return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function test_type()
    {
      return $this->belongsTo(TestType::class, 'test_type_id', 'id');
    }

    public function test_template()
    {
      return $this->belongsTo(TestTemplate::class, 'test_template_id', 'id');
    }

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
           $category->attributes['code'] = 'T-'.Str::random(11);
       });
   }
   

}

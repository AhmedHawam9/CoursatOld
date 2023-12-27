<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Year;
use App\Subject;
use App\Type;

class Stage extends Model
{
    protected  $guarded = [];
    protected $table = "stages";
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function years()
    {
        return $this->hasMany(Year::class, 'stage_id');
    }
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'stage_id');
    }
    public function types()
    {
        return $this->hasMany(Type::class, 'stage_id');
    }
}

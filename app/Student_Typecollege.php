<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student_Typecollege extends Model
{
    use SoftDeletes;
    protected  $guarded = [];
    
    protected $table = "students_typescollege";
    public function student(){
        return $this->belongsTo(User::class, 'student_id');
    }

    public function typescollege()
    {
        return $this->belongsTo(TypesCollege::class, 'typecollege_id');
    }

    protected $appends = ['type_format'];

    public function getTypeFormatAttribute()
    {
        if ($this->type == 0) {
            return 'اشتراك';
        }
        elseif ($this->type == 1) {
            return 'شراء';
        } elseif ($this->type == 2) {
            return 'طلب انضمام';
        }elseif ($this->type == 3) {
            return 'scan Qrcode';
        }elseif ($this->type == 4) {
            return 'Dashboard';
        }
    }
}

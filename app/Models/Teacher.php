<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'email',
        'phone',
        'gender',
        'qualification',
        'experience',
        'image',
        'status',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    //
    protected $fillable = [
        'name',
        'section',
        'class_code',  
        'school_id',
        'capacity',
        'description',
        'status',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'class_id');
    }

    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class, 'school_class_id');
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class, 'class_id');
    }

    public function marks()
    {
        return $this->hasMany(Mark::class, 'class_id');
    }
}

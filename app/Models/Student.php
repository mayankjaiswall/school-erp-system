<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'school_id',
        'class_id',
        'admission_no',
        'roll_no',
        'name',
        'email',
        'phone',
        'gender',
        'dob',
        'address',
        'photo',
        'status',
    ];

    protected $casts = [
        'dob' => 'date',
        'status' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function parents()
    {
        return $this->belongsToMany(ParentModel::class, 'student_parent', 'student_id', 'parent_id')
            ->withPivot('relationship')
            ->withTimestamps();
    }

    public function teacherRemarks()
    {
        return $this->hasMany(TeacherRemark::class);
    }
}

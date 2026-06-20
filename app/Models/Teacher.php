<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'school_id',
        'primary_subject_id',
        'employee_code',
        'name',
        'email',
        'phone',
        'gender',
        'qualification',
        'experience',
        'experience_years',
        'joining_date',
        'designation',
        'image',
        'status',
    ];

    protected $casts = [
        'joining_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class);
    }

    public function primarySubject()
    {
        return $this->belongsTo(Subject::class, 'primary_subject_id');
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function teacherRemarks()
    {
        return $this->hasMany(TeacherRemark::class);
    }

    public function getDisplayExperienceAttribute(): ?int
    {
        return $this->experience_years ?? $this->experience;
    }
}

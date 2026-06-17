<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'exam_type',
        'exam_date',
        'academic_year',
        'status',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'status' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}

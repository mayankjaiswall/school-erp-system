<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $table = 'parents';

    protected $fillable = [
        'user_id',
        'father_name',
        'mother_name',
        'phone',
        'alternate_phone',
        'email',
        'occupation',
        'address',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_parent_links', 'parent_id', 'student_id')
            ->withPivot('relationship')
            ->withTimestamps();
    }
}

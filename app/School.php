<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $casts = [
        'approve_time' => 'int',
    ];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'school_teachers', 'school_id', 'teacher_id');
    }

    public function managers()
    {
        return $this->teachers()->where('is_manager', '>', 0);
    }

    public function creator()
    {
        return $this->belongsTo(Teacher::class, 'creator_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}

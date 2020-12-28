<?php

namespace App;

class Student extends User
{
    protected static $identityColumn = 'is_student';

    public function followed_teachers()
    {
        return $this->belongsToMany(Teacher::class, 'student_follows', 'student_id', 'teacher_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'student_school_id');
    }
}

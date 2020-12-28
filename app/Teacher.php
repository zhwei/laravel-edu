<?php

namespace App;

class Teacher extends User
{
    protected static $identityColumn = 'is_teacher';

    public function following_students()
    {
        return $this->belongsToMany(Student::class, 'student_follows', 'teacher_id', 'student_id');
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, 'school_teachers', 'teacher_id', 'school_id');
    }
}

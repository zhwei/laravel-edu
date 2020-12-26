<?php

namespace App;

class Teacher extends User
{
    protected static $identityColumn = 'is_teacher';

    public function following_students()
    {
        return $this->belongsToMany(Student::class, 'student_follows', 'teacher_id', 'student_id');
    }
}

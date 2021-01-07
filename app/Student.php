<?php

namespace App;

use Illuminate\Notifications\Notifiable;

class Student extends User
{
    use Notifiable;

    protected static $identityColumn = 'is_student';

    public function followed_teachers()
    {
        return $this->belongsToMany(Teacher::class, 'student_follows', 'student_id', 'teacher_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'student_school_id');
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'students.' . $this->id;
    }
}

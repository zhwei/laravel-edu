<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolTeacher extends Model
{
    protected $table = 'school_teachers';

    protected $fillable = [
        'school_id',
        'teacher_id',
    ];

    public static function isSchoolManager(int $schoolId, int $teacherId)
    {
        return self::query()
                ->where('teacher_id', $teacherId)
                ->where('school_id', $schoolId)
                ->where('is_manager', '>', 0)
                ->count() > 0;
    }
}

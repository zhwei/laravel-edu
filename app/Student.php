<?php

namespace App;

use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * Class Student
 * @package App
 *
 * @Schema(type="object", description="学生详情", properties={
 *     @Property(property="id", type="integer", format="int64", description="ID"),
 *     @Property(property="name", type="string", description="姓名"),
 *     @Property(property="email", type="string", description="邮箱"),
 *     @Property(property="created_at", type="string", description="注册时间"),
 * })
 */
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

<?php

namespace App;

use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * Class Teacher
 * @package App
 *
 * @Schema(type="object", description="老师详情", properties={
 *     @Property(property="id", type="integer", format="int64", description="ID"),
 *     @Property(property="name", type="string", description="姓名"),
 *     @Property(property="email", type="string", description="邮箱"),
 *     @Property(property="created_at", type="string", description="注册时间"),
 * })
 */
class Teacher extends User
{
    protected static $identityColumn = 'is_teacher';

    public function following_students()
    {
        return $this->belongsToMany(Student::class, 'student_follows', 'teacher_id', 'student_id');
    }
}

<?php

namespace Tests\Unit;

use App\Student;
use App\SystemAdmin;
use App\Teacher;
use App\User;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    public function testGlobalScope()
    {
        $user = factory(User::class)->create();
        $student = factory(Student::class)->create();
        $teacher = factory(Teacher::class)->create();
        $systemAdmin = factory(SystemAdmin::class)->create();

        self::assertNotNull(Student::find($student->id));
        self::assertNull(Teacher::find($student->id));
        self::assertNull(Student::find($user->id));

        self::assertNotNull(Teacher::find($teacher->id));
        self::assertNull(Student::find($teacher->id));
        self::assertNull(Teacher::find($user->id));

        self::assertNotNull(SystemAdmin::find($systemAdmin->id));
        self::assertNull(Teacher::find($systemAdmin->id));
        self::assertNull(SystemAdmin::find($user->id));
    }
}

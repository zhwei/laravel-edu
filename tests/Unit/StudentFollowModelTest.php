<?php

namespace Tests\Unit;

use App\Student;
use App\StudentFollow;
use App\Teacher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StudentFollowModelTest extends TestCase
{
    use DatabaseTransactions;

    public function testStudentFollowRelations()
    {
        $students = factory(Student::class)->times(3)->create();
        $teacher = factory(Teacher::class)->create();

        foreach ($students as $student) {
            $sf = new StudentFollow();
            $sf->student_id = $student->id;
            $sf->teacher_id = $teacher->id;
            $sf->save();
        }

        foreach ($students as $student) {
            self::assertContains($teacher->id, $student->followed_teachers->pluck('id')->all());
        }
        self::assertSame(
            $students->pluck('id')->all(),
            $teacher->following_students->pluck('id')->all()
        );
    }
}

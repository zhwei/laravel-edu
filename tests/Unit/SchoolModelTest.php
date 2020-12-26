<?php

namespace Tests\Unit;

use App\School;
use App\SchoolTeacher;
use App\Teacher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SchoolModelTest extends TestCase
{
    use DatabaseTransactions;

    public function testTeacherRelations()
    {
        $schoolCreator = factory(Teacher::class)->create();
        $schoolTeachers = factory(Teacher::class)->times(3)->create();
        $schoolManagers = factory(Teacher::class)->times(3)->create();
        factory(Teacher::class)->times(3)->create(); // other teachers

        $school = factory(School::class)->create([
            'creator_id' => $schoolCreator->id,
        ]);
        foreach ($schoolTeachers as $teacher) {
            factory(SchoolTeacher::class)->create([
                'school_id' => $school->id,
                'teacher_id' => $teacher->id,
            ]);
        }
        foreach ($schoolManagers as $teacher) {
            factory(SchoolTeacher::class)->create([
                'school_id' => $school->id,
                'teacher_id' => $teacher->id,
                'is_manager' => time(),
            ]);
        }

        self::assertSame($schoolCreator->id, $school->creator->id);
        self::assertSame($schoolManagers->pluck('id')->all(), $school->managers->pluck('id')->all());
        self::assertSame(
            $schoolTeachers->merge($schoolManagers)->pluck('id')->all(),
            $school->teachers->pluck('id')->all()
        );
    }
}

<?php

namespace Tests\Feature;

use App\School;
use App\Student;
use App\Teacher;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Arr;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TeacherApiTest extends TestCase
{
    use DatabaseTransactions;

    public function testListFollowing()
    {
        $school = factory(School::class)->create();
        $teacher = factory(Teacher::class)->create();
        $students = factory(Student::class)->times(3)->create([
            'student_school_id' => $school->id,
        ]);
        $teacher->following_students()->attach($students, ['created_at' => Carbon::now()]);

        Passport::actingAs($teacher);
        $resp = $this->get('/teachers/students/following');
        throw_if($resp->exception, $resp->exception);
        $resp->assertSuccessful();
        self::assertSame($students->pluck('id')->all(), Arr::pluck($resp->json()['items'], 'id'));
    }
}

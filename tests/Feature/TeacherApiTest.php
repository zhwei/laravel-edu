<?php

namespace Tests\Feature;

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
        $teacher = factory(Teacher::class)->create();
        $students = factory(Student::class)->times(3)->create();
        $teacher->following_students()->attach($students, ['created_at' => Carbon::now()]);

        Passport::actingAs($teacher);
        $resp = $this->get('/teachers/students/following');
        $resp->assertSuccessful();
        self::assertSame($students->pluck('id')->all(), Arr::pluck($resp->json()['items'], 'id'));
    }
}

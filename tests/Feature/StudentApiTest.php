<?php

namespace Tests\Feature;

use App\Student;
use App\StudentFollow;
use App\Teacher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Arr;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StudentApiTest extends TestCase
{
    use DatabaseTransactions;

    public function testFollowing()
    {
        $student = factory(Student::class)->create();
        $teachers = factory(Teacher::class)->times(3)->create();
        foreach ($teachers as $teacher) {
            $sf = new StudentFollow();
            $sf->student_id = $student->id;
            $sf->teacher_id = $teacher->id;
            $sf->save();
        }

        Passport::actingAs($student);

        $response = $this->getJson('/students/following');
        throw_if($response->exception, $response->exception);
        self::assertSame($teachers->pluck('id')->all(), Arr::pluck($response->json()['items'], 'id'));
    }

    public function testFollowAndUnfollow()
    {
        $student = factory(Student::class)->create();
        $teacher = factory(Teacher::class)->create();
        self::assertSame([], $student->followed_teachers->pluck('id')->all());

        Passport::actingAs($student);

        // 关注
        $resp = $this->post("/students/follow/{$teacher->id}");
        $resp->assertSuccessful();
        self::assertSame([$teacher->id], $student->fresh()->followed_teachers->pluck('id')->all());

        // 取关
        $resp = $this->delete("/students/unfollow/{$teacher->id}");
        $resp->assertSuccessful();
        self::assertSame([], $student->fresh()->followed_teachers->pluck('id')->all());
    }
}

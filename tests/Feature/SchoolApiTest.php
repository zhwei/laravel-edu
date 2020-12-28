<?php

namespace Tests\Feature;

use App\School;
use App\SchoolTeacher;
use App\Student;
use App\Teacher;
use Faker\Generator;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SchoolApiTest extends TestCase
{
//    use DatabaseTransactions;

    public function testCreate()
    {
        $teacher = factory(Teacher::class)->create();
        Passport::actingAs($teacher);

        $this->postJson('/schools/create', ['name' => 'example school 1'])->assertSuccessful();
        $this->postJson('/schools/create', ['name' => 'example school 2'])->assertSuccessful();
        self::assertSame(2, School::whereCreatorId($teacher->id)->whereApproveTime(0)->count());
    }

    public function testCreateStudent()
    {
        $school = factory(School::class)->create([
            'creator_id' => factory(Teacher::class)->create()->id,
        ]);
        $teacher = factory(Teacher::class)->create();
        Passport::actingAs($teacher);

        // 非学校管理员无权操作
        $fail = $this->postJson("/schools/students/{$school->id}/create", [
            'name' => 'Tom',
            'email' => app(Generator::class)->email,
            'password' => '123',
        ]);
        self::assertSame(400, $fail->getStatusCode());
        self::assertSame('仅允许学校管理员操作', $fail->json()['message']);

        // 学校管理员
        SchoolTeacher::forceCreate([
            'teacher_id' => $teacher->id,
            'school_id' => $school->id,
            'is_manager' => time(),
        ]);
        $this->postJson("/schools/students/{$school->id}/create", [
            'name' => 'Tom',
            'email' => $email = app(Generator::class)->email,
            'password' => '123',
        ])->assertSuccessful();
        self::assertSame($school->id, Student::whereEmail($email)->first()->school->id);
    }

    public function testInviteTeacher()
    {
        $school = factory(School::class)->create([
            'creator_id' => factory(Teacher::class)->create()->id,
        ]);
        $teacher = factory(Teacher::class)->create();
        SchoolTeacher::forceCreate([
            'teacher_id' => $teacher->id,
            'school_id' => $school->id,
            'is_manager' => time(),
        ]);
        Passport::actingAs($teacher);

        // 邀请已注册老师
        $newTeacher = factory(Teacher::class)->create();
        $this->postJson("/schools/teachers/{$school->id}/invite", [
            'name' => 'Tom',
            'email' => $newTeacher->email,
            'password' => '123',
        ])->assertSuccessful();
        self::assertSame(1, SchoolTeacher::whereSchoolId($school->id)->whereTeacherId($newTeacher->id)->count());

        // 邀请未注册老师
        $this->postJson("/schools/teachers/{$school->id}/invite", [
            'name' => 'Tom',
            'email' => $email = app(Generator::class)->email,
            'password' => '123',
        ])->assertSuccessful();
        $newCreateTeacher = Teacher::whereEmail($email)->first();
        self::assertSame(1, SchoolTeacher::whereSchoolId($school->id)->whereTeacherId($newCreateTeacher->id)->count());
    }
}

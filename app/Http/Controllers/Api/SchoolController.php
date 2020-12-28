<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Components\ErrorMessage;
use App\Http\Controllers\Controller;
use App\School;
use App\SchoolTeacher;
use App\Student;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;

class SchoolController extends Controller
{
    /**
     * @Post(
     *     description="申请创建学校（老师）",
     *     path="/schools/create",
     *     tags={ "Schools" },
     *     operationId="create",
     *     security={{ "bearerAuth":{} }},
     *     @RequestBody(description="学校信息", @JsonContent(
     *          @Property(property="name", description="学校名称"),
     *     )),
     *     @Response(response="200", description="创建成功"),
     *     @Response(response="400", description="失败", @JsonContent(ref="#/components/schemas/ErrorMessage")),
     * )
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $school = new School();
        $school->name = $request->json('name');
        $school->creator_id = $request->user()->id;
        $school->save();
    }

    public function list()
    {
    }

    /**
     * @Post(
     *     description="创建学生",
     *     path="/schools/students/{schoolId}/create",
     *     tags={ "Schools" },
     *     operationId="create",
     *     security={{ "bearerAuth":{} }},
     *     @Parameter(name="schoolId", in="path", required=true, @Schema(type="integer", description="学校 ID")),
     *     @RequestBody(description="学生信息", required=true, @JsonContent(type="object", properties={
     *          @Property(property="name", type="string", description="姓名"),
     *          @Property(property="email", type="string", description="邮箱"),
     *          @Property(property="password", type="string", description="密码"),
     *     })),
     *     @Response(response="200", description="创建成功"),
     *     @Response(response="400", description="失败", @JsonContent(ref="#/components/schemas/ErrorMessage")),
     * )
     */
    public function createStudent(int $schoolId, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string'
        ]);

        // 检查当前用户是否学校管理员
        self::assertIsSchoolManager($schoolId, $request);

        $student = new Student();
        $student->name = $request->json('name');
        $student->email = $request->json('email');
        $student->password = bcrypt($request->json('password'));
        $student->is_student = time();
        $student->student_school_id = $schoolId;
        $student->save();
    }

    private static function assertIsSchoolManager(int $schoolId, Request $request)
    {
        if (SchoolTeacher::isSchoolManager($schoolId, $request->user()->id) === false) {
            throw new ErrorMessage('仅允许学校管理员操作');
        }
    }

    /**
     * @Post(
     *     description="邀请老师",
     *     path="schools/teachers/{schoolId}/invite",
     *     tags={ "Schools" },
     *     operationId="inviteTeacher",
     *     security={{ "bearerAuth":{} }},
     *     @Parameter(name="schoolId", in="path", required=true, @Schema(type="integer", description="学校 ID")),
     *     @RequestBody(description="老师信息", required=true, @JsonContent(type="object", properties={
     *          @Property(property="name", type="string", description="姓名"),
     *          @Property(property="email", type="string", description="邮箱"),
     *          @Property(property="password", type="string", description="密码"),
     *     })),
     *     @Response(response="200", description="创建成功"),
     *     @Response(response="400", description="失败", @JsonContent(ref="#/components/schemas/ErrorMessage")),
     * )
     */
    public function inviteTeacher(int $schoolId, Request $request)
    {
        self::assertIsSchoolManager($schoolId, $request);

        $this->validate($request, ['email' => 'required|email']);
        $email = $request->json('email');

        // 使用 email 判定邀请的用户是否存在
        if (!$user = User::whereEmail($email)->first()) {
            $this->validate($request, [
                'name' => 'required|string',
                'password' => 'required|string',
                'email' => 'unique:users',
            ]);
            // 不存在则新建老师
            $user = new Teacher();
            $user->name = $request->json('name');
            $user->email = $email;
            $user->password = bcrypt($request->json('password'));
            $user->is_teacher = time();
            $user->save();
        }

        // 绑定老师和学校
        SchoolTeacher::firstOrCreate(['school_id' => $schoolId, 'teacher_id' => $user->id]);
    }

    public function approve(int $schoolId, string $action)
    {
    }

    public function sendMessage(int $schoolId, int $studentId)
    {
    }
}

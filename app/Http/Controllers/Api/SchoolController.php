<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Components\ErrorMessage;
use App\Http\Controllers\Controller;
use App\School;
use App\SchoolTeacher;
use App\Student;
use App\SystemAdmin;
use App\Teacher;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Put;
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
     *     @RequestBody(description="学校信息", required=true, @JsonContent(
     *          @Property(property="name", description="学校名称", example="MIT"),
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

    /**
     * 学校列表
     *
     * @Get(
     *     path="/schoolds",
     *     description="学校列表",
     *     tags={ "Schools" },
     *     operationId="list",
     *     security={{ "bearerAuth":{} }},
     *     @Parameter(name="lastId", in="query", @Schema(type="integer", description="翻页 id")),
     *     @Response(response="200", description="学校分页结果", @JsonContent(type="object", properties={
     *          @Property(property="lastId", type="integer", description="翻页 id"),
     *          @Property(property="items", type="array", description="学校列表", @Items(type="object", properties={
     *              @Property(property="id", type="integer", description="ID"),
     *              @Property(property="name", type="string", description="学校名称"),
     *              @Property(property="approve_at", type="string", description="学校通过审批的时间"),
     *              @Property(property="created_at", type="string", description="申请时间"),
     *          })),
     *     }))
     * )
     */
    public function list(Request $request)
    {
        $lastId = (int)$request->query('lastId');
        $isSystemAdmin = SystemAdmin::checkIdentity($request->user());

        $query = School::query();

        if ($isSystemAdmin) {
            $lastId > 0 && $query->where('id', '>', $lastId); // 系统管理员时，只添加翻页条件
            $query->limit(OpenApi::PAGE_LIMIT);
        } else {
            // 非系统管理员，根据关联表查询所有学校 id
            // 使用关联表的 id 作为翻页凭据
            $schoolIds = SchoolTeacher::whereTeacherId($request->user()->id)
                ->when($lastId, function (Builder $query) use ($lastId) {
                    $query->where('id', '>', $lastId);
                })
                ->limit(OpenApi::PAGE_LIMIT)
                ->pluck('school_id', 'id');
            $currentLastId = $schoolIds->keys()->last();
            $query->whereIn('id', $schoolIds->values());
        }

        $schools = $query->get(['id', 'name', 'approve_time', 'created_at']);
        return [
            'lastId' => $currentLastId ?? $schools->last()->id ?? 0,
            'items' => array_map(
                function (School $school) {
                    return [
                        'id' => $school->id,
                        'name' => $school->name,
                        'approve_at' => $school->approve_time ? date('Y-m-d H:i:s', $school->approve_time) : '',
                        'created_at' => $school->created_at->toDateTimeString(),
                    ];
                },
                $schools->all(),
            )
        ];
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

    // 验证当前访问用户是否指定学校的管理员
    private static function assertIsSchoolManager(int $schoolId, Request $request)
    {
        if (false === SchoolTeacher::isSchoolManager($schoolId, $request->user()->id)) {
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

    /**
     * 系统管理员审批学校申请
     *
     * @Put(
     *     path="/schools/approve/{schoolId}/{action}",
     *     description="系统管理员审批学校申请",
     *     tags={ "Schools" },
     *     operationId="approve",
     *     security={{ "bearerAuth":{} }},
     *     @Parameter(name="schoolId", in="path", required=true, @Schema(type="integer", description="学校 ID")),
     *     @Parameter(name="action", in="path", required=true,
     *          @Schema(type="string", description="学校 ID", enum={ "pass", "reject" })
     *     ),
     *     @Response(response="200", description="成功"),
     *     @Response(response="400", description="失败", @JsonContent(ref="#/components/schemas/ErrorMessage")),
     * )
     */
    public function approve(int $schoolId, string $action)
    {
        throw_unless(in_array($action, ['pass', 'reject']), new ErrorMessage('非法请求'));

        $school = School::find($schoolId);
        throw_unless($school, new ErrorMessage('找不到该学校'));

        if ('pass' === $action) {
            $school->approve_time = time();
            $school->save();
        }
    }

    public function sendMessage(int $schoolId, int $studentId)
    {
    }
}

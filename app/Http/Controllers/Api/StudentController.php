<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\StudentFollow;
use App\Teacher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use OpenApi\Annotations\Delete;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;

class StudentController extends Controller
{
    /**
     * @Get(
     *     description="获取当前登陆学生关注的老师列表",
     *     path="/students/following",
     *     tags={ "Students" },
     *     operationId="listFollowing",
     *     security={{ "bearerAuth":{} }},
     *     @Parameter(name="lastId", in="query", @Schema(type="integer", description="翻页 id")),
     *     @Response(response="200", description="老师列表", @JsonContent(type="object", properties={
     *          @Property(property="lastId", type="integer", description="用于控制翻页"),
     *          @Property(property="items", type="array", description="老师列表", @Items(ref="#/components/schemas/Teacher")),
     *     })),
     *     @Response(response="401", description="未登录")
     * )
     */
    public function listFollowing(Request $request)
    {
        $user = $request->user();

        // 每页最多返回 100 个
        $teacherIds = StudentFollow::query()
            ->whereStudentId($user->id)
            ->when($lastId = $request->query('lastId'), function (Builder $query) use ($lastId) {
                return $query->where('id', '>', $lastId);
            })
            ->limit(OpenApi::PAGE_LIMIT)
            ->pluck('teacher_id', 'id');
        $teachers = Teacher::query()
            ->findMany($teacherIds, ['id', 'name', 'email', 'created_at'])
            ->keyBy('id');

        // 按关注顺序返回老师列表
        $result = ['lastId' => $teacherIds->last()->id ?? 0, 'items' => []];
        foreach ($teacherIds as $teacherId) {
            $result['items'][] = $teachers[$teacherId];
        }
        return $result;
    }

    /**
     * @Post(
     *     description="当前登陆学生关注指定老师",
     *     path="/students/follow/{teacherId}",
     *     tags={ "Students" },
     *     operationId="follow",
     *     security={{ "bearerAuth":{} }},
     *     @Parameter(name="teacherId", in="path", required=true, @Schema(type="integer", description="老师 ID")),
     *     @Response(response="200", description="关注成功"),
     *     @Response(response="401", description="未登录"),
     * )
     */
    public function follow(int $teacherId, Request $request)
    {
        StudentFollow::firstOrCreate([
            'student_id' => $request->user()->id,
            'teacher_id' => $teacherId,
        ]);
    }

    /**
     * @Delete(
     *     description="当前登陆学生取消关注指定老师",
     *     path="/students/unfollow/{teacherId}",
     *     tags={ "Students" },
     *     operationId="unfollow",
     *     security={{ "bearerAuth":{} }},
     *     @Parameter(name="teacherId", in="path", required=true, @Schema(type="integer", description="老师 ID")),
     *     @Response(response="200", description="取消成功"),
     *     @Response(response="401", description="未登录"),
     * )
     */
    public function unfollow(int $teacherId, Request $request)
    {
        StudentFollow::where([
            'student_id' => $request->user()->id,
            'teacher_id' => $teacherId,
        ])->delete();
    }
}

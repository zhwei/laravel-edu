<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Student;
use App\StudentFollow;
use Illuminate\Http\Request;
use OpenApi\Annotations\Get;

class TeacherController extends Controller
{
    /**
     * @Get(
     *     path="/teachers/students/following",
     *     tags={ "Teachers" },
     *     operationId="listFollowing",
     *     security={{ "bearerAuth":{} }},
     *     @Parameter(name="lastId", in="query", @Schema(type="integer", description="翻页 id")),
     *     @Response(response="200", description="学生分页结果", @JsonContent(type="object", properties={
     *          @Property(property="lastId", type="integer", description="翻页 id"),
     *          @Property(property="items", type="array", description="学生列表", @Items(type="object", properties={
     *              @Property(property="id", type="integer", description="ID"),
     *              @Property(property="name", type="string", description="学生姓名"),
     *          })),
     *     }))
     * )
     */
    public function listFollowing(Request $request)
    {
        $query = StudentFollow::whereTeacherId($request->user()->id);
        if ($lastId = (int)$request->query('lastId')) {
            $query->where('id', '>', $lastId);
        }
        $studentIds = $query->limit(OpenApi::PAGE_LIMIT)->pluck('student_id', 'id');

        $students = Student::whereIn('id', $studentIds->values())
            ->get(['id', 'name'])
            ->keyBy('id');
        $items = [];
        foreach ($studentIds as $studentId) {
            $items[] = [
                'id' => $students[$studentId]->id,
                'name' => $students[$studentId]->name,
            ];
        }

        return [
            'lastId' => $studentIds->keys()->last()->id ?? 0,
            'items' => $items,
        ];
    }
}

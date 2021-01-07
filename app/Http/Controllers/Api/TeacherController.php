<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\SchoolTeacher;
use App\Student;
use App\StudentFollow;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;

class TeacherController extends Controller
{
    /**
     * 所有学生
     *
     * @Get(
     *     path="/teachers/students/teaching",
     *     tags={ "Teachers" },
     *     operationId="listTeachingStudents",
     *     security={{ "bearerAuth":{} }},
     *     @Parameter(name="lastId", in="query", @Schema(type="integer", description="翻页 id")),
     *     @Response(response="200", description="学生分页结果", @JsonContent(type="object", properties={
     *          @Property(property="lastId", type="integer", description="翻页 id"),
     *          @Property(property="items", type="array", description="学生列表", @Items(type="object", properties={
     *              @Property(property="id", type="integer", description="ID"),
     *              @Property(property="name", type="string", description="学生姓名"),
     *              @Property(property="school_id", type="integer", description="学校 ID"),
     *              @Property(property="school_name", type="string", description="学校名称"),
     *          })),
     *     }))
     * )
     */
    public function listTeaching(Request $request)
    {
        $schoolIds = SchoolTeacher::whereTeacherId($request->user()->id)
            ->pluck('school_id')
            ->all();
        if (!$schoolIds) {
            return self::paginator(0, []);
        }

        $lastId = (int)$request->query('lastId');
        $students = Student::with('school:id,name')
            ->whereIn('student_school_id', $schoolIds)
            ->when($lastId > 0, function (Builder $query) use ($lastId) {
                $query->where('id', '>', $lastId);
            })
            ->limit(OpenApi::PAGE_LIMIT)
            ->get(['id', 'name', 'student_school_id']);

        return self::paginator(
            $students->last()->id ?? 0,
            array_map([$this, 'buildStudent'], $students->all())
        );
    }

    /**
     * 所有关注的学生
     *
     * @Get(
     *     path="/teachers/students/following",
     *     tags={ "Teachers" },
     *     operationId="listFollowingStudents",
     *     security={{ "bearerAuth":{} }},
     *     @Parameter(name="lastId", in="query", @Schema(type="integer", description="翻页 id")),
     *     @Response(response="200", description="学生分页结果", @JsonContent(type="object", properties={
     *          @Property(property="lastId", type="integer", description="翻页 id"),
     *          @Property(property="items", type="array", description="学生列表", @Items(type="object", properties={
     *              @Property(property="id", type="integer", description="ID"),
     *              @Property(property="name", type="string", description="学生姓名"),
     *              @Property(property="school_id", type="integer", description="学校 ID"),
     *              @Property(property="school_name", type="string", description="学校名称"),
     *          })),
     *     }))
     * )
     */
    public function listFollowing(Request $request)
    {
        $lastId = (int)$request->query('lastId');

        $query = StudentFollow::whereTeacherId($request->user()->id);
        $lastId > 0 && $query->where('id', '>', $lastId);
        $studentIds = $query->limit(OpenApi::PAGE_LIMIT)
            ->pluck('student_id', 'id');

        $students = Student::with('school:id,name')
            ->whereIn('id', $studentIds->values()->all())
            ->get(['id', 'name', 'student_school_id'])
            ->keyBy('id');
        $items = [];
        foreach ($studentIds as $studentId) {
            $items[] = $this->buildStudent($students[$studentId]);
        }

        return self::paginator($studentIds->keys()->last()->id ?? 0, $items);
    }

    private function buildStudent(Student $student)
    {
        return [
            'id' => $student->id,
            'name' => $student->name,
            'school_id' => $student->student_school_id,
            'school_name' => $student->school->name ?? '',
        ];
    }

    private static function paginator(int $lastId, array $items)
    {
        return ['lastId' => $lastId, 'items' => $items];
    }
}

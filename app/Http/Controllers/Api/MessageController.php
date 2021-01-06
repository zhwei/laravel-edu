<?php

namespace App\Http\Controllers\Api;

use App\Events\ChatEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;

class MessageController extends Controller
{
    /**
     * @Post(
     *     summary="老师发送消息给学生",
     *     path="/messages/teacher-talk/{studentId}",
     *     tags={ "Messages" },
     *     operationId="teacherTalk",
     *     security={{ "bearerAuth":{} }},
     *     @RequestBody(description="消息内容", required=true, @JsonContent(
     *          @Property(property="content", description="消息文本", example="你好呀"),
     *     )),
     *     @Response(response="200", description="发送成功")
     * )
     */
    public function teacherTalk(int $studentId, Request $request)
    {
        $this->validate($request, [
            'content' => 'required|string',
        ]);

        $teacher = $request->user();
        broadcast(new ChatEvent($teacher->id, $studentId, $request->json('content'), $teacher));
    }

    /**
     * @Post(
     *     summary="学生发送消息给老师",
     *     path="/messages/student-talk/{teacherId}",
     *     tags={ "Messages" },
     *     operationId="teacherTalk",
     *     security={{ "bearerAuth":{} }},
     *     @RequestBody(description="消息内容", required=true, @JsonContent(
     *          @Property(property="content", description="消息文本", example="你好呀"),
     *     )),
     *     @Response(response="200", description="发送成功")
     * )
     */
    public function studentTalk(int $teacherId, Request $request)
    {
        $this->validate($request, [
            'content' => 'required|string',
        ]);

        $student = $request->user();
        broadcast(new ChatEvent($teacherId, $student->id, $request->json('content'), $student));
    }
}

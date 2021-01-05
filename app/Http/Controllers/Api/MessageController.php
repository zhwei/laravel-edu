<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;
use Pusher\Pusher;

class MessageController extends Controller
{
    /**
     * @Post(
     *     summary="发起聊天/发送消息",
     *     path="/messages/talk/{toId}",
     *     tags={ "Messages" },
     *     operationId="talk",
     *     security={{ "bearerAuth":{} }},
     *     @RequestBody(description="消息内容", required=true, @JsonContent(
     *          @Property(property="content", description="消息文本", example="你好呀"),
     *     )),
     *     @Response(response="200", description="学校分页结果", @JsonContent(type="object", properties={
     *          @Property(property="channel", type="string", description="频道"),
     *     }))
     * )
     *
     * @param Request $request
     */
    public function auth(Request $request)
    {
        $request->user();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Components\ErrorMessage;
use App\Http\Controllers\Api\Components\UserInfo;
use App\Http\Controllers\Controller;
use App\Services\LineService;
use App\User;
use Carbon\Carbon;
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


class LineController extends Controller
{

    /**
     * @Post(
     *     summary="绑定 Line 账号",
     *     path="/line/bind",
     *     tags={ "Line" },
     *     operationId="lineBind",
     *     security={{ "bearerAuth":{} }},
     *     @RequestBody(description="绑定信息", required=true, @JsonContent(
     *          @Property(property="token", type="string", description="Line ID Token (jwt)"),
     *     )),
     *     @Response(response="200", description="成功"),
     * )
     */
    public function bind(Request $request, LineService $line)
    {
        $this->validate($request, [
            'token' => 'required|string',
        ]);

        $lineUserId = $line->verify($request->json('token'));
        $user = $request->user();
        $user->line_id = $lineUserId;
        $user->save();
    }

    /**
     * @Put(
     *     summary="解绑 Line 账号",
     *     path="/line/unbind",
     *     tags={ "Line" },
     *     operationId="lineUnBind",
     *     security={{ "bearerAuth":{} }},
     *     @Response(response="200", description="成功"),
     * )
     */
    public function unbind(Request $request)
    {
        $user = $request->user();
        $user->line_id = '';
        $user->save();
    }

    /**
     * @Post(
     *     summary="使用 line id token 查询所有绑定的用户",
     *     path="/auth/line/users",
     *     tags={ "Line" },
     *     operationId="getUsersByLineToken",
     *     @RequestBody(description="绑定信息", required=true, @JsonContent(
     *          @Items(type="object", ref="#/components/schemas/UserInfo"),
     *     )),
     *     @Response(response="200", description="成功"),
     * )
     */
    public function users(Request $request, LineService $line)
    {
        $this->validate($request, [
            'token' => 'required|string',
        ]);

        $jwt = $request->json('token');
        $lineUserId = $line->verify($jwt);
        $users = User::whereLineId($lineUserId)->get();
        $result = [];
        foreach ($users as $user) {
            $result[] = new UserInfo($user, '', 0);
        }
        return $result;
    }

    /**
     * @Post(
     *     summary="使用 line id token 登陆指定用户",
     *     path="/auth/line/login",
     *     tags={ "Line" },
     *     operationId="loginByLineTokenAndUserId",
     *     @RequestBody(description="绑定信息", required=true, @JsonContent(
     *          @Property(property="token", type="string", description="Line ID Token (jwt)"),
     *          @Property(property="userId", type="integer", description="User ID"),
     *     )),
     *     @Response(response="200", description="login success", @JsonContent(ref="#/components/schemas/UserInfo")),
     * )
     */
    public function login(Request $request, LineService $line)
    {
        $this->validate($request, [
            'token' => 'required|string',
            'userId' => 'required|integer',
        ]);

        $jwt = $request->json('token');
        $lineUserId = $line->verify($jwt);

        $user = User::whereLineId($lineUserId)->whereId($request->json('userId'))->first();
        if (!$user) {
            throw new ErrorMessage('非法请求');
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $tokenResult->token->expires_at = new Carbon('+1 day');
        $tokenResult->token->save();
        return new UserInfo(
            $user,
            $tokenResult->accessToken,
            $tokenResult->token->expires_at->timestamp,
        );
    }
}

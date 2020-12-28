<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Components\ErrorMessage;
use App\Http\Controllers\Controller;
use App\Teacher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations\Info;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;

/**
 * Class AuthController
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller
{
    /**
     * @Post(
     *     path="/auth/register",
     *     tags={ "Auth" },
     *     operationId="register",
     *     @RequestBody(description="注册信息", required=true, @JsonContent(type="object", properties={
     *          @Property(property="name", type="string", example="Tom", description="姓名"),
     *          @Property(property="email", type="string", example="a@b.com", description="邮箱"),
     *          @Property(property="password", type="string", description="密码"),
     *          @Property(property="password_confirmation", type="string", description="密码验证"),
     *     })),
     *     @Response(response="200", description="注册成功"),
     *     @Response(response="400", description="注册失败", @JsonContent(ref="#/components/schemas/ErrorMessage")),
     * )
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $json = $request->json();
        $teacher = new Teacher();
        $teacher->name = $json->get('name');
        $teacher->email = $json->get('email');
        $teacher->password = bcrypt($json->get('password'));
        $teacher->is_teacher = time();
        $teacher->save();

        return new JsonResponse('');
    }

    /**
     * @param Request $request
     *
     * @Post(
     *     path="/auth/login",
     *     tags={ "Auth" },
     *     operationId="login",
     *     @RequestBody(required=true, @JsonContent(type="object", properties={
     *          @Property(property="email", type="string", example="a@b.com", description="邮箱"),
     *          @Property(property="password", type="string", description="密码"),
     *     })),
     *     @Response(response="200", description="login success", @JsonContent(type="object", properties={
     *          @Property(property="access_token", type="string", description="Bearer Token，不带 Bearer 前缀"),
     *          @Property(property="expires_at", type="integer", example="1608987937", format="int64", description="过期时间"),
     *     })),
     *     @Response(response="400", description="login fail", @JsonContent(ref="#/components/schemas/ErrorMessage")),
     * )
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only(['email', 'password'])) === false) {
            throw new ErrorMessage(__('auth.login_failed'));
        }

        /** @var User $user */
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $tokenResult->token->expires_at = new Carbon('+1 day');
        $tokenResult->token->save();

        return [
            'access_token' => $tokenResult->accessToken,
            'expires_at' => $tokenResult->token->expires_at->timestamp,
        ];
    }
}

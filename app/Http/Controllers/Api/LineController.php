<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Components\ErrorMessage;
use App\Http\Controllers\Api\Components\UserInfo;
use App\Http\Controllers\Controller;
use App\Services\LineService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LineController extends Controller
{
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

    public function unbind(Request $request)
    {
        $user = $request->user();
        $user->line_id = '';
        $user->save();
    }

    /**
     * 使用 line jwt token 查询所有绑定的用户
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
     * 使用 line jwt token 登陆指定用户
     */
    public function login(Request $request, LineService $line)
    {
        $this->validate($request, [
            'token' => 'required|string',
            'userId' => 'required|number',
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

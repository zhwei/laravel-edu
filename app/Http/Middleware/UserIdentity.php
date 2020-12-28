<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\Components\ErrorMessage;
use App\User;
use Closure;

class UserIdentity
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param class-string[]|User[] $identities
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$identities)
    {
        $user = $request->user();
        foreach ($identities as $identity) {
            if ($identity::checkIdentity($user)) {
                return $next($request);
            }
        }

        throw new ErrorMessage('无权操作');
    }
}

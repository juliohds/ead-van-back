<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use App\Role;
use App\NetworkUser;
use App\Exceptions\DeniedException;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class AdminMiddleware
{
    private static $ROLES = [Role::ADMIN, Role::NET_ADMIN];
    public function handle($request, Closure $next, $guard = null)
    {
        $nu = $request->auth->networkUser($request->currentNetwork->id);
        if(!(in_array($nu->role->id,AdminMiddleware::$ROLES))){
            throw new DeniedException;
        }

        return $next($request);
    }
}
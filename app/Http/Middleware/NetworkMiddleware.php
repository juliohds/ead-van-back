<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Network;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class NetworkMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $idNetwork = $request->route()[2]['idNetwork'];
        if($idNetwork != null){
            $request->currentNetwork = Network::find($idNetwork);
        }
        if($request->currentNetwork == null) {
            throw new \App\Exceptions\NotFoundException;
        }
        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use DB;
class DBTransactionMiddleware
{
	public function handle($request, Closure $next)
	{
		DB::beginTransaction();

		$response = $next($request);

		DB::commit();

		return $response;
	}
}

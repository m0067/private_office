<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class CheckBlockedUser
 * @package App\Http\Middleware
 */
class CheckBlockedUser
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_blocked) {
            return response()->json('Your account has been blocked.', 403);
        }

        return $next($request);
    }
}

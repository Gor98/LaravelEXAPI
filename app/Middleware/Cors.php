<?php

namespace App\Middleware;

use App\Common\Bases\Request;
use Closure;

class Cors
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public final function handle(Request $request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}

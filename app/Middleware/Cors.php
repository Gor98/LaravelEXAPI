<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    final public function handle(Request $request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}

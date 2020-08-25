<?php

namespace App\Middleware;

use Illuminate\Http\Request;
use Closure;
use phpDocumentor\Reflection\Types\Mixed_;

class AcceptJson
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    final public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}

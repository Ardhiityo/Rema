<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Csp\AddCspHeaders;
use Symfony\Component\HttpFoundation\Response;

class AddCspUnlessLogViewer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('log-viewer*')) {
            return $next($request);
        }

        return app(AddCspHeaders::class)->handle($request, $next);
    }
}

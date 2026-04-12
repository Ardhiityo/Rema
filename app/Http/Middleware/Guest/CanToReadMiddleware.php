<?php

namespace App\Http\Middleware\Guest;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CanToReadMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guest()) {
            session()->put('path_read_temporary', request()->fullUrl());
            return redirect()->route('login')
                ->with('unauthenticated', 'Please log in to your account first');
        }

        if (is_null(Auth::user()->email_verified_at)) {
            session()->put('path_read_temporary', request()->fullUrl());
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}

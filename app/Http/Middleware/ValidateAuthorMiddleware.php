<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateAuthorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            $author = $user->author;
            if (is_null($author->nim) || is_null($author->study_program_id)) {
                return redirect()->route('profile.index')
                    ->with('alert', 'Please complete your profile first.');
            }
            if ($author->status !== 'approve') {
                return redirect()->route('profile.index')
                    ->with('alert', "Your account status is {$author->status}, please contact the academic department.");
            }
        }
        return $next($request);
    }
}

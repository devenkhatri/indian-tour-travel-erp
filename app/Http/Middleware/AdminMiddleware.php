<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access the administration portal.');
        }

        $user = auth()->user();
        if (! $user->isAdmin() && ! $user->isStaff()) {
            abort(403, 'Unauthorized access to Administration Portal.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access the B2B Partner Portal.');
        }

        $user = auth()->user();
        if (! $user->isAgent() && ! $user->isAdmin()) {
            abort(403, 'Unauthorized access to B2B Agent Portal.');
        }

        return $next($request);
    }
}

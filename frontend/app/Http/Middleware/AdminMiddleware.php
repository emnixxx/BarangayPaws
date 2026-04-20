<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Ensures the authenticated user has the 'admin' role and is active.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Not logged in
        if (!$user) {
            return redirect()->route('login');
        }

        // Not an admin — redirect with error
        if ($user->role !== 'admin') {
            abort(403, 'Access denied. Admins only.');
        }

        // Account inactive
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been deactivated.',
            ]);
        }

        return $next($request);
    }
}
<?php
// app/Http/Middleware/RoleMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Usage in routes:  ->middleware('role:admin,organiser')
     * Checks if authenticated user has one of the listed roles.
     */
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been disabled.');
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}

// ─── Register in app/Http/Kernel.php ─────────────────────────
// Add to $routeMiddleware:
// 'role' => \App\Http\Middleware\RoleMiddleware::class,

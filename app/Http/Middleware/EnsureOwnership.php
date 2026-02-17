<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $parameter): Response
    {
        $resource = $request->route($parameter);

        // Allow admins to access any resource (they bypass ownership checks)
        if ($request->user()?->isAdmin()) {
            return $next($request);
        }

        // Check if the resource exists and belongs to the current user
        if (!$resource || $resource->user_id !== $request->user()?->id) {
            abort(403, 'Unauthorized action. You can only access your own resources.');
        }

        return $next($request);
    }
}

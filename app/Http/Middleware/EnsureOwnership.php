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

        if (!$resource || $resource->user_id !== $request->user()?->id) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}

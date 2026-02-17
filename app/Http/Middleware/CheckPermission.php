<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $action  The action to check (create, read, update, delete)
     */
    public function handle(Request $request, Closure $next, string $action): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            abort(401, 'Unauthenticated.');
        }

        $user = $request->user();

        // Define permission mappings for each role
        $permissions = [
            Role::ADMIN => ['create', 'read', 'update', 'delete'],
            Role::READER => ['read'],
            Role::CUSTOMER => ['create', 'read', 'delete'], // Customer can't update
        ];

        // Check if user has permission for this action
        $hasPermission = false;
        
        foreach ($permissions as $roleSlug => $allowedActions) {
            if ($user->hasRole($roleSlug) && in_array($action, $allowedActions)) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            abort(403, "Unauthorized. You do not have permission to {$action} this resource.");
        }

        return $next($request);
    }
}
                    
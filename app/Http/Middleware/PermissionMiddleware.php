<?php
//  controllers/Middleware/PermissionMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Usage in routes:
     *   ->middleware('permission:news')
     *   ->middleware('permission:news,gallery')
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // SuperAdmin bypasses all permission checks
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to access this section.');
    }
}
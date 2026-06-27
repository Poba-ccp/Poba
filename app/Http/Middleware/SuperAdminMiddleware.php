<?php
// FILE: app/Http/Middleware/SuperAdminMiddleware.php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();


        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Only Super Administrators can access this section.');
        }

        return $next($request);
    }
}

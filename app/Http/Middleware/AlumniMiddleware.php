<?php
//  controllers/Middleware/AlumniMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AlumniMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('alumni')->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to continue.');
        }

        $alumni = Auth::guard('alumni')->user();

        if (! $alumni->is_active) {
            Auth::guard('alumni')->logout();
            return redirect()->route('login')
                ->with('error', 'Your account has been suspended. Please contact support.');
        }

        return $next($request);
    }
}
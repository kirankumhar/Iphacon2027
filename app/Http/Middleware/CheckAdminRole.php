<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();

        if (!$admin || !in_array(strtolower((string)$admin->role), array_map('strtolower', $roles))) {
            abort(403, 'Access denied. Insufficient privileges.');
        }

        return $next($request);
    }
}
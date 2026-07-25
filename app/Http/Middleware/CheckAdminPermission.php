<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminPermission
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Please login to continue.');
        }

        $admin = Auth::guard('admin')->user();

        if (!$admin->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Your account has been deactivated.');
        }

        // Check if admin has required permission
        if (!empty($permissions)) {
            if (!$admin->hasAnyPermission($permissions)) {
                abort(403, 'You do not have permission to access this resource.');
            }
        }

        return $next($request);
    }
}
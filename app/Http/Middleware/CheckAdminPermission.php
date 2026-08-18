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

        // Restrict Moderator to only moderator dashboard, abstract routes, password change, and logout
        if ($admin->isModerator()) {
            $isDashboardRoute = $request->routeIs('admin.moderator.dashboard') || $request->routeIs('admin.dashboard') || $request->is('admin/moderator/dashboard') || $request->is('admin/dashboard');
            $isAbstractRoute  = $request->routeIs('admin.abstracts.*') || $request->is('admin/abstracts*');
            $isProfileRoute   = $request->routeIs('admin.profile.*') || $request->routeIs('admin.user.update.password') || $request->is('admin/profile/*') || $request->is('admin/update-password');
            $isLogoutRoute    = $request->routeIs('admin.logout') || $request->is('admin/logout');

            if (!$isDashboardRoute && !$isAbstractRoute && !$isProfileRoute && !$isLogoutRoute) {
                if ($request->expectsJson() || $request->ajax()) {
                    abort(403, 'Access denied. Moderators can only access abstract management.');
                }
                return redirect()->route('admin.moderator.dashboard')->with('error', 'Access denied. Moderators can only access abstract management.');
            }
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
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Check
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Kiểm tra xem người dùng đã xác thực chưa
        if (!Auth::check()) {
            return response('Unauthorized', 401);
        }

        // Kiểm tra xem người dùng có vai trò phù hợp không
        $user = Auth::guard('sanctum')->user();
        $permissionsViaRoles = $user->getPermissionsViaRoles();

        foreach ($roles as $role) {
            foreach ($permissionsViaRoles as $permission) {
                if ($permission->name === $role) {
                    return $next($request);
                }
            }
        }

        // Nếu không có vai trò phù hợp, trả về lỗi Unauthorized
        return response('Unauthorized', 403);
    }
}

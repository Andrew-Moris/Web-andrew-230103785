<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }
        
        // Check if user has admin permissions
        if (!$user->userPermissions || !in_array('users', $user->userPermissions->permissions)) {
            return redirect()->route('permissions')->with('error', 'عذراً، هذه الصفحة مخصصة للأدمن فقط. لا تملك صلاحيات الوصول لإدارة المستخدمين.');
        }
        
        return $next($request);
    }
}

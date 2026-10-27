<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        if (Auth::guard('web')->check()) {
            return redirect()->route('home')->with('error', 'You do not have access to the admin area.');
        }

        return redirect()->route('admin.login')->with('error', 'Sign in as an administrator to continue.');
    }
}

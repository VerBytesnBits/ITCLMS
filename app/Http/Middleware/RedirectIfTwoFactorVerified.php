<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
class RedirectIfTwoFactorVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

       
        if (!$user->google2fa_enabled || empty($user->google2fa_secret)) {
            return redirect()->route('dashboard');
        }

       
        if (session('google2fa', false)) {
            return redirect()->route('dashboard');
        }


        return $next($request);
    }
}

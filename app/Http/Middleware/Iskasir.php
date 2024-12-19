<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Iskasir
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
        if (Auth::user() && Auth::user()->role == 'kasir') {
            return $next($request);
        }
        if ($request->is('api/*')) {
            // It's an API route
            return response()->json(
                ['message' => 'Tidak memiliki hak akses'],
                401);
        } else {
            // It's a web route
            return redirect('/');
        }
    }
}

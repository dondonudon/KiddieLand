<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('status')) {
            $status = $request->session()->get('status');
            if ($status !== 'logged in') {
                return redirect('login');
            } else {
                return $next($request);
            }
        } else {
            return redirect('login');
        }
    }
}

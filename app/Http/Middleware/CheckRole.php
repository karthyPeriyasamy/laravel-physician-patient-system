<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $adminRole = $request->user()->is_admin;
        $physicianRole = $request->user()->is_physician;
        if ($adminRole === 1) {
            $request->request->add([
                'scope' => 'admin'
            ]);
        } elseif ($physicianRole === 1) {
            $request->request->add([
                'scope' => 'physician'
            ]);
        } else {
            $request->request->add([
                'scope' => 'user'
            ]);
        }
        return $next($request);
    }
}

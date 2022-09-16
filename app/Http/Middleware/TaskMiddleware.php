<?php

namespace App\Http\Middleware;

use Closure;

class TaskMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if($user->role==1){
            return $next($request);
        }
        return response("Not authorised",401);
    }
}

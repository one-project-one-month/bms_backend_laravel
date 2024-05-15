<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAuthMiddleware
{
    use HttpResponses;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->role=='admin'){
            return response()->json([
                'status' => false,
                'error' => 'Unauthenticated'
            ]);
        }
        // if ($request->is('web') || $request->is('web/*')) {
        //     return $this->error([
        //         'Unauthenticated!',
        //         '401'
        //     ]);
        // }
        return $next($request);
    }
}

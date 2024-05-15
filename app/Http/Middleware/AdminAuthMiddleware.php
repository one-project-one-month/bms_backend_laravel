<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    use HttpResponses;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if(!($guard == "admin" && Auth::guard($guard)->check())){
        //     return $this->error(
        //         'Unauthenticated!',
        //         '401'
        //     );

        // }
        // if ($request->is('admin') || $request->is('admin/*')) {
        //     return $this->error(
        //         'Unauthenticated!',
        //         '401'
        //     );
        // }
        if(Auth::user()->role == 'user'){
            // return $this->error(
            //     'Unauthenticated!',
            //     '401'
            // );
            return response()->json([
                'status' => false,
                'error' => 'Unauthenticated'
            ]);
        }
        return $next($request);


    }
}

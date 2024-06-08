<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class checkDeactivateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::user()->isDeactivate == 1) {
            return response()->json([
                'status'=> false,
                'message' => 'Deactivate user cannot make this action'
            ], 401);
        }

        if (Auth::user()->isDelete == 1) {
            return response()->json([
                'status'=> false,
                'message' => 'The account had already freezed!'
            ], 401);
        }

        return $next($request);
    }
}

<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
       
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
    }   



    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     // return $request;
    //     if ($request->expectsJson()) {
    //         return response()->json(['error' => 'Unauthenticated.'], 401);
    //     }
    //     // if ($request->is('admin') || $request->is('admin/*')) {
    //     //     return redirect()->guest('/login/admin');
    //     // }
    //     // if ($request->is('writer') || $request->is('writer/*')) {
    //     //     return redirect()->guest('/login/writer');
    //     // }
    //     // return redirect()->guest(route('login'));
    // }
}

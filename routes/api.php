<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminAuthController;
// use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('v1/admin/login', [AdminAuthController::class, 'login']);

Auth::routes();
Route::post('user/login',[LoginController::class,'login']);




// Route::middleware(['admin_auth'])->group(function() {
//     Route::prefix('admin')->group(function(){
//         Route::get('admin',[AdminController::class,'index']);
//         Route::get('userlist',[UserController::class,'index']);
//     });
// });


    Route::group(['prefix' => 'v1','middleware' => 'auth:sanctum'], function () {
        Route::middleware(['admin_auth'])->prefix('admin')->group(function() {
            Route::post('/admin-register', [AdminAuthController::class, 'register']);
            Route::post('/user-register',[UserController::class,'userRegister']);
            // Route::get('/user_accept_or_reject', [AdminController::class, 'getAllPendingUsers']);
            // Route::patch('/user_accept_or_reject/{accountNo}', [AdminController::class, 'userAcceptOrReject']);
            Route::get('admin',[AdminController::class,'index']);
            Route::get('userlist',[UserController::class,'index']);

        });


    // Route::middleware(['user_auth'])->prefix('user')->group(function() {

    //     Route::get('home', function() {
    //         return "This is user home";
    //     });

    //     Route::get('testing',[UserController::class,'index']);

    // });

});



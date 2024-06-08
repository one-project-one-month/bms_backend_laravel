<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\TransactionController;

use App\Http\Controllers\DepositWithdrawController;
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



Route::post('v1/admins/login', [AdminAuthController::class, 'login']);


    Route::group(['prefix' => 'v1','middleware' => 'auth:sanctum'], function () {
        Route::middleware(['admin_auth', 'check.deactivate'])->group(function() {

            Route::post('admins', [AdminController::class, 'insert']);
            Route::get('/admins', [AdminController::class, 'index'])->middleware('check.softDelete');


            Route::prefix('admins')->group(function(){

                Route::post('actions',[AdminController::class,'accountActions']);


                Route::prefix('users')->group(function(){
                    Route::post('/registrations',[UserController::class,'userRegister']);

                    Route::post('/transactions', [TransactionController::class, 'createTransaction']);
                    
                });
            });
           
            
            Route::post('/users/account-deactivate',[UserController::class,'accountDeactivate']);
            Route::post('/users/account-delete',[UserController::class,'accountDelete']);
            // Route::post('/admin-register', [AdminController::class, 'insert']);
            Route::get('admin',[AdminController::class,'index']);
            Route::get('userlist',[UserController::class,'index']);


            // Route::post('deposit',[DepositWithdrawController::class,'deposit']);
            // Route::post('withdraw', [DepositWithdrawController::class,'withdraw']);


            Route::get('users', [UserController::class, 'index']);
            // Route::get('users/transactions')
        });


});



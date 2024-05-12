<?php

use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('v1/admin/login', [AdminAuthController::class, 'login'])->middleware('auth:admin');
// Route::post('v1/admin/register', [AdminAuthController::class, 'register']);

Route::group(['prefix' => 'v1','middleware' => 'auth:sanctum'], function () {


});

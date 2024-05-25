<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Traits\HttpResponses;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AdminResource;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Notifications\Notifiable;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminAuthController extends Controller
{
    use HasApiTokens, HasFactory, Notifiable, HttpResponses;



    public function login(AdminLoginRequest $request)
    {

        try {

            $validated = $request->validated();
            $admin = Admin::where('userName', $validated['userName'])->first();

            if (!Hash::check($validated['password'], $admin->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot Login',
                ], 401);
            }


            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $admin->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

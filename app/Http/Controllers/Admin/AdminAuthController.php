<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class AdminAuthController extends Controller
{
    use HasApiTokens, HasFactory, Notifiable;

   

    public function login(AdminLoginRequest $request)
    {
        
        try {
           
            $validated = $request->validated();
            $admin = Admin::where('name', $validated['name'])->first();

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

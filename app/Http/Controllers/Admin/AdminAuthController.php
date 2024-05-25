<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AdminLoginRequest;
use App\Services\AdminService;
use App\Traits\HttpResponses;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    use HasApiTokens, HasFactory, Notifiable, HttpResponses;


    protected $admin;

    public function __construct(AdminService $admin)
    {
        $this->admin = $admin;
    }


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
                'message' => 'Admin Logged In Successfully',
                'token' => $admin->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function register(AdminLoginRequest $request)
    {

        $data = $request->validated();

        $data['name'] = $request->name;
        $data['password'] = Hash::make($request->password);
       
      
        $adminSuccess = $this->admin->insert($data);

        if ($adminSuccess) {
            return response()->json('an admin is created successfully', 200);
        }
        return response()->json(['message'=>'fail to create'],403);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Traits\HttpResponses;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AdminLoginRequest;
use App\Services\AdminService;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    use HasApiTokens, HasFactory, Notifiable, HttpResponses;

    protected  $admin;

    public function __construct(AdminService $admin)
    {
       
        $this->admin = $admin;
    }

    public function login(AdminLoginRequest $request)
    {

        try {

            $validated = $request->validated();

           

            $admin = $this->admin->getAdminByEmail($request->email);


            // check accout has been freezed?
            if($admin->isDelete == 1)
            {
                
                return $this->error(null, "Cannot Login, the accout has been freezed", 404);

            }
            
            if ($admin == null) {
               return $this->error(null, "Cannot Login, the admin couldn't found", 404);
            }

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

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->success(null, "Successfully logout", 204);

    }

}

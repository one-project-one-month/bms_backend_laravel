<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'=> 'required',
            'password' => 'required'
        ]);
    
      
         $admin = Admin::where('email', $request->email)->first();

     

    
        if (!Auth::guard('admin')->attempt(['email' => $request->email, 'password' => 
        $request->password], $request->remember)) {
        return 'abc';
     }
       

         
        $token_name = $request->input('token_name', 'api-token');

        $abilities = $request->input('abilities', [
            'order:create',
            'order:view',
            'WLR3:check_availability',
            3
        ]);

        $token = $admin->createToken($token_name, $abilities);

        return $this->success([
            'user' => $admin,
            'token' => $token
            // 'token' => $admin->createToken('API Token of ' . $admin->name)->plainTextToken,
        ]);
    
        // If authentication successful, generate token
       
    }
}

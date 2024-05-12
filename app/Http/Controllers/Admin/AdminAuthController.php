<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class AdminAuthController extends Controller
{
    use HasApiTokens, HasFactory, Notifiable;

    public function register(Request $request){
        $admin = Admin::create([
            "name" => $request->name,
 
            "password" => Hash::make($request->password)
        ]);

        $admin = Admin::where('name', $request->name)->first();
        dd(Auth::guard('admin')->attempt($request->only(['name','password'])));

        if(Auth::attempt($request->only(['name','password']))){
            $token = $admin->createToken("name")->plainTextToken;
            return response()->json($token);
        }
        return response()->json(['message'=>'user not found'],403);
    }

    public function login(Request $request)
    {
        dd(Auth::user() instanceof User);
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'password' => 'required'
            ]);
            $admin = Admin::where('name',$request->name)->first();

            // if (!Hash::check($request->password, $admin->password)) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'name & Password does not match with our record.',
            //     ], 401);
            // }
           
            // dd(Auth::attempt($request->only(['name', 'password'])));


            // if($validateUser->fails()){
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'validation error',
            //         'errors' => $validateUser->errors()
            //     ], 401);
            // }
            if (Auth::attempt(['name' => $request->name, 'password' => $request->password])) {
                dd('hi');
            }
            // dd(Auth::attempt(['name' => $request->name, 'password' => $request->password]));
            // if(!Auth::attempt($request->only(['name', 'password']))){
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'name & Password does not match',
            //     ], 401);
            // }

           dd('no');

            // return response()->json([
            //     'status' => true,
            //     'message' => 'User Logged In Successfully',
            //     'token' => $admin->createToken("API TOKEN")->plainTextToken
            // ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

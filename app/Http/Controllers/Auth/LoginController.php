<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator as confirm;

class LoginController extends Controller
{
    use HasApiTokens,HttpResponses;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // $this->middleware('guest:admin')->except('logout');
        // $this->middleware('guest:user')->except('logout');
    }

    public function home(){
        return $this->success(['message'=> 'this is home']);
    }
    public function login(Request $request){
        // return $request->all();
        $validation = Validator::make($request->all(),[
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);
        if($validation->fails()){
            return $this->error('',$validation->errors()->all,442);
        }

        $user = User::where('email', $request->email)->first();

        if ($user && $user->status == 'accept') {
            if($user->status == 'accept'){
                if (Hash::check($request->password, $user->password)) {

                    return $this->success(
                        [
                            'user' => $user,
                            'token' => $user->createToken(time())->plainTextToken
                        ],
                        'Login Success',
                        200
                    );
                }
            }else{
                return $this->error('Wait to accept your account by admin');
            }

        }

        return $this->error(
            'unauthenticate',
            'Credentials Do Not Match',
            '401'
        );
    }
}

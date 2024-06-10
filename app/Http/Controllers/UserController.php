<?php

namespace App\Http\Controllers;

use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\GenerateCodeNumber;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserAccActionRequest;

class UserController extends Controller
{
    use HasApiTokens,HttpResponses,GenerateCodeNumber;

    protected $user;

    function __construct(UserService $user){
        $this->user = $user;
    }

    public function index(){
        $userList = UserResource::collection(User::get());
        return $userList;
    }

    public function userRegister(StoreUserRequest $request)
    {
        $data = $request->validated();
        $userCode = $this->generateUniqueCode('Cus');
        $townshipNo = substr($request->townshipCode,3);
        $accountNo = $townshipNo.'-'.rand(0,999).'-'.rand(0,999).'-'.rand(0,999);

        $data['userCode'] = $userCode;
        $data['accountNo'] = $accountNo;
        $data['isDelete'] = false;
        $data['isDeactivate'] = false;
        $data['adminId'] = Auth::user()->id;

        // return $data;
        $user = $this->user->insert($data);
        $resUser = UserResource::make($user);

        if($user){
            return $this->success($resUser,'success',200);
        }

    }

    public function userAccActions(UserAccActionRequest $request){
        $validateData = $request->validated();
        $accountNo = $validateData['data']['accountNo'];
        $process = $validateData['process'];


        if($process == 'deactivate'){
            return $this->deactivateOrActivate($process,$accountNo);
        }else if($process == 'activate'){
            return $this->deactivateOrActivate($process,$accountNo);
        }else if($process == 'delete'){
            return $this->accountDelete($process,$accountNo);
        }else{
            return response()->json(['message' => 'Invalid action']);
        }

    }

    private function deactivateOrActivate($process,$accountNo){
        $account = $accountNo;
        $process == 'deactivate' ? $status = 1 : $status = 0;

        $user = $this->user->getUserByAccountNo($accountNo);
        // return $user->isDeactivate;

        if($user = null){
            return response()->json(['message' => 'User account not found!'],404);
        }

        // if($user->isDeactivate === $status){
        //     return response()->json([
        //         'message' => $status ?'User account has been already deactivate' :  "The account is already activate"
        //     ]);
        // }

        if($status){

            $accountDeactivate = $this->user->accountDeactivated($status,$accountNo);

            $deactivateAcc = $this->user->getUserByAccountNo($accountNo);
            $resUser = UserResource::make($deactivateAcc);

            return $this->success($resUser,'success',200);
        }else{
            $accountActivate = $this->user->accountDeactivated($status,$accountNo);

            $activateAcc = $this->user->getUserByAccountNo($account);
            $resUser = UserResource::make($activateAcc);
            return $this->success($resUser,'success',200);
        }

    }

    public function accountDelete($process,$accountNo){
        // return $request;
        $status = 1;
        $user = $this->user->getUserByAccountNo($accountNo);
        if($user = null){
            return response()->json([
                'message' => 'User account not found!'
            ]);
        }
        $accountDelete = $this->user->accountDelete($status,$accountNo);

        if($accountDelete){

            $resUser = UserResource::make($user);
            return $this->success($resUser,'success',200);
        }

    }

}

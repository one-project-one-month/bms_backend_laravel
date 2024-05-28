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

    public function userRegister(StoreUserRequest $resquest)
    {
        $data = $resquest->validated();
        $userCode = $this->generateUniqueCode('Cus');
        $townshipNo = substr($resquest->townshipCode,3);
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

    public function accountDeactivate(Request $resquest){
        $status = $resquest->isDeactivate;
        $accountNo = $resquest->accountNo;
        $accountDeactivate = $this->user->accountDeactivated($status,$accountNo);

        if($accountDeactivate){
            $user = $this->user->getUserByAccountNo($accountNo);
            $resUser = UserResource::make($user);

            return $this->success($resUser,'success',200);
        }

    }

    public function accountDelete(Request $resquest){
        // return $resquest;
        $delete = $resquest->isDelete;
        $accountNo = $resquest->accountNo;
        $accountDelete = $this->user->accountDelete($delete,$accountNo);

        if($accountDelete){
            $user = $this->user->getUserByAccountNo($accountNo);
            $resUser = UserResource::make($user);
            return $this->success($resUser,'success',200);
        }

    }

}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use HasApiTokens,HttpResponses;

    function __construct(UserService $user){
        $this->user = $user;
    }

    public function index(){
        $userList = UserResouce::collection(User::get());
        return $userList;
    }

    public function store(StoreUserRequest $resquest)
    {
        $data = $resquest->validated();

        $townshipNo = substr($resquest->townshipCode,3);
        $accountNo = $townshipNo.'-'.rand(0,999).'-'.rand(0,999).'-'.rand(0,999);
        $data['accountNo'] = $accountNo;
        $data['isDelete'] = 0;
        $data['isDeactivate'] = 0;
        $data['status'] = 'pending';
        $data['role'] = 'user';
        // return $data;
        $user = $this->user->insert($data);
        $resUser = UserResource::make($user);

        if($user){
            return $this->success($resUser,'success',200);
        }

    }


}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\HttpResponses;
use App\Services\AdminService;
use App\Http\Resources\AdminResource;
use App\Http\Requests\AdminRegisterRequest;

class AdminController extends Controller
{
    use HttpResponses;

    protected $user, $admin;

    public function __construct(UserService $user, AdminService $admin)
    {
        $this->user = $user;
        $this->admin = $admin;
    }

    public function adminRegister(AdminRegisterRequest $request){
        // one admin is auto-crate when migration starts
        // this function is to create new admin accounts for staff
        // this action can only make by super admin
        $data = $request->validate();
        return $data;
        $admin = $this->admin->insert($data);
        $resAdmin = AdminResource::make($admin);

        if($admin){
            return $this->success($resAdmin,'success',200);
        }
    }

    public function getAllPendingUsers(){
       $users = $this->user->getAllPendingUsers();
        return response()->json($users);
    }

    public function userAcceptOrReject(Request $request, $accountNo){


        // Think about whether admins can update other information except status

        // ** Admin can update only status **
       $userUpdate = $this->user->updateUserStatus($request->status, $accountNo );

        if ($userUpdate) {

            $user = $this->user->getUserByAccountNo($accountNo);

            return response()->json($user);
        }



    }
}

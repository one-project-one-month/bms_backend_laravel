<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\AdminRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\HttpResponses;
use App\Services\AdminService;
use App\Services\UserService;
use App\Traits\GenerateCodeNumber;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    use GenerateCodeNumber, HttpResponses;
    protected $user, $admin;

    public function __construct(UserService $user, AdminService $admin)
    {
        $this->user = $user;
        $this->admin = $admin;
    }


    public function insert(AdminRegisterRequest $request)
    {

        $data = $request->validated();

        $data['adminCode'] = $this->generateUniqueCode('Adm');
        $data['name'] = $request->name;
        $data['password'] = Hash::make($request->password);
        $data['role'] = $request->role;



        $adminSuccess = $this->admin->insert($data);

        if ($adminSuccess) {
            return response()->json([
                'status' => true,
                'message' => 'An Employee is created successfully',
            ]);
        }
        return $this->error(null,'Cannot create', 403);
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

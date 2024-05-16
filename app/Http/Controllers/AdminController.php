<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AdminService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    
    protected $user, $admin;

    public function __construct(UserService $user, AdminService $admin)
    {
        $this->user = $user;
        $this->admin = $admin;
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

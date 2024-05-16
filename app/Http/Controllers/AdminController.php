<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function getAllPendingUsers(){
       $users = User::where('status', 'pending')->get();
        return response()->json($users);
    }

    public function userAcceptOrReject(Request $request, $accountNo){

        // dd($request->status);
        // Think about whether admins can update other information except status

        $user = User::where('accountNo', $accountNo)->first();
        // $user = User::where('accountNo', $accountNo)->first();

       
        if ($user) {
            $user->status = $request->status;

            $user->update();
            
            return response()->json($user);
        }

        
        
    }
}

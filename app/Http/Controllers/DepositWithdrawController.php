<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\HttpResponses;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Services\DepositWithdrawService;
use App\Http\Requests\DepositWithdrawRequest;
use App\Http\Resources\DepositWithdrawResource;

class DepositWithdrawController extends Controller
{
    use HttpResponses;
    protected $user, $depositWithdraw;

    public function __construct(UserService $user,DepositWithdrawService $depositWithdraw){
        $this->user = $user;
        $this->depositWithdraw = $depositWithdraw;
    }

    // deposit money to user account
    // admin and staff can only make this transaction
    public function deposit(DepositWithdrawRequest $request){
        $data = $request->validated();
        $accountNo = $request->accountNo;
        $depositAmount = $request->amount;
        $type = $request->transactionType;

        $user = $this->user->getUserByAccountNo($accountNo);
        if($user->isDeactivate && $user->isDelete){
            return response()->json([
                'success' => false,
                'message' => "Account was freeze!"
            ]);
        }else{
            if($type == 'deposit'){
                $data['adminId'] = Auth::user()->id;

                $currentBalance = $user->balance;

                $totalBalance = $currentBalance+ $depositAmount;

                $depositAcc = $this->user->depositToAccount($totalBalance,$accountNo);

                $deposit = $this->depositWithdraw->insert($data);

                if($depositAcc && $deposit){
                    $user = $this->user->getUserByAccountNo($accountNo);
                    $resUser = UserResource::make($user);
                    $resDeposit = DepositWithdrawResource::make($deposit);

                    return response()->json([
                        'deposit' => $resDeposit,
                        'userAccount' => $resUser,
                        'message' => 'success'
                    ],200);
                }

            }
        }


    }
}
